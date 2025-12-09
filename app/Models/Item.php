<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'stock',
        'stock_minimum',
        'supplier_id',
        'category_id',
        'unit_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function itemBatches()
    {
        return $this->hasMany(ItemBatch::class);
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