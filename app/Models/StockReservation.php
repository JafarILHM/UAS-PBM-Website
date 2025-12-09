<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_batch_id',
        'quantity',
        'user_id',
        'purpose',
        'reserved_until',
        'is_fulfilled',
    ];

    protected $casts = [
        'reserved_until' => 'datetime',
        'is_fulfilled' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemBatch()
    {
        return $this->belongsTo(ItemBatch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}