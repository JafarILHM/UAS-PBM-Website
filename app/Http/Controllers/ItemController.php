<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Import Auth facade
// use Maatwebsite\Excel\Facades\Excel; // For Excel export

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
            'barcode' => 'nullable|unique:items,barcode',
            'name' => 'required|string|max:255',
            'stock_minimum' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $item = Item::create($validated);
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
            'barcode' => ['nullable', Rule::unique('items')->ignore($item->id)],
            'name' => 'required|string|max:255',
            'stock_minimum' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $item->update($validated);
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
            'item_id' => 'required|exists:items,id',
            'batch_number' => 'required|string|max:255',
            'production_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:production_date',
            'qty' => 'required|integer|min:1',
            'date_in' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:date_in',
        ]);

        $item = Item::find($validated['item_id']);

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
            'item_id' => 'required|exists:items,id',
            'item_batch_id' => 'required|exists:item_batches,id',
            'qty' => 'required|integer|min:1',
            'purpose' => 'nullable|string|max:255',
            'date_out' => 'nullable|date',
        ]);

        $item = Item::find($validated['item_id']);
        $itemBatch = ItemBatch::find($validated['item_batch_id']);

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

    public function export()
    {
        // Placeholder for Excel export functionality
        // You would typically use a library like Maatwebsite\Excel here.
        // Example: return Excel::download(new ItemsExport, 'items.xlsx');
        return response()->json(['message' => 'Export functionality is not yet implemented.']);
    }
}
