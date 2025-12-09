<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Maatwebsite\Excel\Facades\Excel; // For Excel export
use App\Exports\ItemsExport; // Import the ItemsExport class
use Milon\Barcode\DNS1D; // Import the DNS1D facade

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['supplier', 'category', 'unit'])->paginate(10);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:items,sku',
            'name' => 'required|string|max:255',
            'stock_minimum' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $item = Item::create($validated);
        $item->barcode = $item->sku; // Set barcode to SKU
        // Generate barcode_base64
        $barcodeSvg = DNS1D::get   ('CODE128', $item->sku, 2, 33, 'black', true);
        $item->barcode_base64 = base64_encode($barcodeSvg);
        $item->save();

        return response()->json($item, 201);
    }

    public function show(Item $item)
    {
        return response()->json($item->load(['supplier', 'category', 'unit', 'itemBatches']));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'sku' => ['required', Rule::unique('items')->ignore($item->id)],
            'barcode' => ['nullable', 'string', Rule::unique('items')->ignore($item->id)], // Updated validation
            'name' => 'required|string|max:255',
            'stock_minimum' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $item->update($validated);

        // If SKU changes, regenerate barcode_base64
        if ($item->isDirty('sku')) {
            $item->barcode = $item->sku; // Update barcode to new SKU
            $barcodeSvg = DNS1D::get   ('CODE128', $item->sku, 2, 33, 'black', true);
            $item->barcode_base64 = base64_encode($barcodeSvg);
            $item->save();
        }
        return response()->json($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }

    public function incoming(Request $request)
    {
        $validated = $request->validate([
            'item_identifier' => 'required|string', // Can be SKU or Barcode
            'batch_number' => 'required|string|max:255',
            'production_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:production_date',
            'qty' => 'required|integer|min:1',
            'date_in' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:date_in',
        ]);

        $item = $this->findItemByIdentifier($validated['item_identifier']);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $operatorId = Auth::id();

        // Find or create item batch
        $itemBatch = ItemBatch::firstOrNew(
            ['item_id' => $item->id, 'batch_number' => $validated['batch_number']]
        );

        // Update batch details even if it exists
        $itemBatch->production_date = $validated['production_date'];
        $itemBatch->expiry_date = $validated['expiry_date'];
        $itemBatch->quantity += $validated['qty']; // Add new quantity
        $itemBatch->save();


        // Create incoming item record
        IncomingItem::create([
            'item_id' => $item->id,
            'item_batch_id' => $itemBatch->id,
            'qty' => $validated['qty'],
            'operator_id' => $operatorId,
            'date_in' => $validated['date_in'] ?? now(),
            'deadline' => $validated['deadline'],
        ]);

        // Update item total stock
        $item->stock += $validated['qty'];
        $item->save();

        return response()->json(['message' => 'Item incoming recorded successfully', 'item' => $item], 200);
    }

    public function outgoing(Request $request)
    {
        $validated = $request->validate([
            'item_identifier' => 'required|string', // Can be SKU or Barcode
            'item_batch_id' => 'required|exists:item_batches,id',
            'qty' => 'required|integer|min:1',
            'purpose' => 'nullable|string|max:255',
            'date_out' => 'nullable|date',
        ]);

        $item = $this->findItemByIdentifier($validated['item_identifier']);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        $itemBatch = ItemBatch::find($validated['item_batch_id']);

        if (!$itemBatch || $itemBatch->item_id !== $item->id) {
            return response()->json(['message' => 'Item batch not found or does not belong to the specified item.'], 404);
        }

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $operatorId = Auth::id();

        if ($validated['qty'] > $item->stock) {
            return response()->json(['message' => 'Not enough stock in warehouse.'], 400);
        }
        if ($validated['qty'] > $itemBatch->quantity) {
            return response()->json(['message' => 'Not enough stock in specified batch.'], 400);
        }

        // Update batch quantity
        $itemBatch->quantity -= $validated['qty'];
        $itemBatch->save();

        // Create outgoing item record
        OutgoingItem::create([
            'item_id' => $item->id,
            'item_batch_id' => $itemBatch->id,
            'qty' => $validated['qty'],
            'operator_id' => $operatorId,
            'purpose' => $validated['purpose'],
            'date_out' => $validated['date_out'] ?? now(),
        ]);

        // Update item total stock
        $item->stock -= $validated['qty'];
        $item->save();

        return response()->json(['message' => 'Item outgoing recorded successfully', 'item' => $item], 200);
    }

    private function findItemByIdentifier(string $identifier): ?Item
    {
        return Item::where('sku', $identifier)
                   ->orWhere('barcode', $identifier)
                   ->first();
    }

    public function export()
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }

    public function showBarcode(Item $item)
    {
        if ($item->barcode_base64) {
            return response()->json(['barcode_base64' => $item->barcode_base64]);
        }
        return response()->json(['message' => 'Barcode not found for this item.'], 404);
    }

    public function scan($code)
    {
        $item = Item::with(['supplier', 'category', 'unit', 'itemBatches'])
                    ->where('sku', $code)
                    ->orWhere('barcode', $code)
                    ->first();

        if ($item) {
            return response()->json($item);
        }

        return response()->json(['message' => 'Item not found.'], 404);
    }
}
