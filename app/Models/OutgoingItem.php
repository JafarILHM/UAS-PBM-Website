<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'operator_id', 
        'qty',
        'purpose',
        'date_out',
    ];

    public function item() { return $this->belongsTo(Item::class); }

    public function user() {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
