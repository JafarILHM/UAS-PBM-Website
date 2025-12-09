<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_batch_id',
        'qty',
        'operator_id',
        'purpose',
        'date_out',
    ];

    protected $casts = [
        'date_out' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemBatch()
    {
        return $this->belongsTo(ItemBatch::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}