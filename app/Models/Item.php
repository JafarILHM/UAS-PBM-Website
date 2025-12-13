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
        'description',
        'category_id',
        'unit_id', 
        'stock',
        'stock_minimum',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // TAMBAHKAN RELASI INI
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
