<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'description',
        'category_id',
        'unit',
        'stock',
        'stock_minimum',
        'image'
    ];

    // Relasi: Satu barang milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
