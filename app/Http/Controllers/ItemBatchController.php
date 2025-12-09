<?php

namespace App\Http\Controllers;

use App\Models\ItemBatch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemBatchController extends Controller
{
    public function index()
    {
        $itemBatches = ItemBatch::with('item')->paginate(10);
        return response()->json($itemBatches);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'batch_number' => ['required', 'string', 'max:255', Rule::unique('item_batches')->where(function ($query) use ($request) {
                return $query->where('item_id', $request->item_id);
            })],
            'production_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:production_date',
            'quantity' => 'required|integer|min:0',
        ]);

        $itemBatch = ItemBatch::create($validated);
        return response()->json($itemBatch, 201);
    }

    public function show(ItemBatch $itemBatch)
    {
        return response()->json($itemBatch->load('item'));
    }

    public function update(Request $request, ItemBatch $itemBatch)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'batch_number' => ['required', 'string', 'max:255', Rule::unique('item_batches')->ignore($itemBatch->id)->where(function ($query) use ($request) {
                return $query->where('item_id', $request->item_id);
            })],
            'production_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:production_date',
            'quantity' => 'required|integer|min:0',
        ]);

        $itemBatch->update($validated);
        return response()->json($itemBatch);
    }

    public function destroy(ItemBatch $itemBatch)
    {
        // Before deleting, ensure the item's stock is adjusted if this batch contributes to it
        // This logic is already handled by incoming/outgoing when items are moved.
        // For a direct batch deletion, you might want to prevent deletion if quantity > 0
        if ($itemBatch->quantity > 0) {
            return response()->json(['message' => 'Cannot delete batch with outstanding quantity.'], 400);
        }
        $itemBatch->delete();
        return response()->json(null, 204);
    }
}