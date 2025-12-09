<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\StockReservation;
use Illuminate\Http\Request;

class StockReservationController extends Controller
{
    public function index()
    {
        $reservations = StockReservation::with(['item', 'itemBatch', 'user'])->paginate(10);
        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'item_batch_id' => 'nullable|exists:item_batches,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'nullable|string|max:255',
            'reserved_until' => 'nullable|date|after_or_equal:' . now()->toDateString(),
        ]);

        $item = Item::find($validated['item_id']);

        if (isset($validated['item_batch_id'])) {
            $itemBatch = ItemBatch::find($validated['item_batch_id']);
            if ($validated['quantity'] > $itemBatch->quantity) {
                return response()->json(['message' => 'Not enough stock in the specified batch for reservation.'], 400);
            }
        } else {
            if ($validated['quantity'] > $item->stock) {
                return response()->json(['message' => 'Not enough total stock for reservation.'], 400);
            }
        }

        $reservation = StockReservation::create([
            'item_id' => $validated['item_id'],
            'item_batch_id' => $validated['item_batch_id'] ?? null,
            'quantity' => $validated['quantity'],
            'user_id' => auth()->user()->id,
            'purpose' => $validated['purpose'] ?? null,
            'reserved_until' => $validated['reserved_until'] ?? null,
        ]);

        // Deduct reserved quantity from stock
        if (isset($validated['item_batch_id'])) {
            $itemBatch->decrement('quantity', $validated['quantity']);
        } else {
            $item->decrement('stock', $validated['quantity']);
        }

        return response()->json($reservation->load(['item', 'itemBatch', 'user']), 201);
    }

    public function show(StockReservation $stockReservation)
    {
        return response()->json($stockReservation->load(['item', 'itemBatch', 'user']));
    }

    public function update(Request $request, StockReservation $stockReservation)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'item_batch_id' => 'nullable|exists:item_batches,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'nullable|string|max:255',
            'reserved_until' => 'nullable|date|after_or_equal:' . now()->toDateString(),
            'is_fulfilled' => 'boolean',
        ]);

        // Hitung selisih stok
        $originalQuantity = $stockReservation->quantity;
        $difference = $validated['quantity'] - $originalQuantity;

        // Update stok jika reservasi belum terpenuhi
        if (!$stockReservation->is_fulfilled && $difference !== 0) {
            if ($stockReservation->item_batch_id) {
                $itemBatch = ItemBatch::find($stockReservation->item_batch_id);
                if ($difference > 0 && $difference > $itemBatch->quantity) {
                    return response()->json(['message' => 'Not enough stock in batch to increase reservation.'], 400);
                }
                $itemBatch->decrement('quantity', max($difference,0));
                if ($difference < 0) $itemBatch->increment('quantity', abs($difference));
            } else {
                $item = Item::find($stockReservation->item_id);
                if ($difference > 0 && $difference > $item->stock) {
                    return response()->json(['message' => 'Not enough total stock to increase reservation.'], 400);
                }
                $item->decrement('stock', max($difference,0));
                if ($difference < 0) $item->increment('stock', abs($difference));
            }
        }

        $stockReservation->update($validated);

        return response()->json($stockReservation->load(['item', 'itemBatch', 'user']));
    }

    public function destroy(StockReservation $stockReservation)
    {
        if (!$stockReservation->is_fulfilled) {
            if ($stockReservation->item_batch_id) {
                $itemBatch = ItemBatch::find($stockReservation->item_batch_id);
                if ($itemBatch) {
                    $itemBatch->increment('quantity', $stockReservation->quantity);
                }
            } else {
                $item = Item::find($stockReservation->item_id);
                if ($item) {
                    $item->increment('stock', $stockReservation->quantity);
                }
            }
        }

        $stockReservation->delete();
        return response()->json(null, 204);
    }

    public function fulfill(StockReservation $stockReservation)
    {
        if ($stockReservation->is_fulfilled) {
            return response()->json(['message' => 'Reservation is already fulfilled.'], 400);
        }

        $stockReservation->update(['is_fulfilled' => true]);

        return response()->json([
            'message' => 'Reservation fulfilled successfully.',
            'reservation' => $stockReservation->load(['item', 'itemBatch', 'user'])
        ]);
    }
}
