<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    use HasFactory;

    // Sesuaikan dengan migrasi database kamu
    protected $fillable = [
        'item_id',
        'operator_id', 
        'supplier_id',
        'qty',
        'batch',
        'production_date',
        'date_in',
        'deadline',
    ];

    public function item() { return $this->belongsTo(Item::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }

    // Relasi ke User tapi nama kolom foreign key-nya 'operator_id'
    public function user() {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
