<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'batch_number',
        'production_date',
        'expiry_date',
        'quantity',
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function incomingItems()
    {
        return $this->hasMany(IncomingItem::class);
    }

    public function outgoingItems()
    {
        return $this->hasMany(OutgoingItem::class);
    }

    public function stockReservations()
    {
        return $this->hasMany(StockReservation::class);
    }
}