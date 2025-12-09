<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_batch_id',
        'qty',
        'operator_id',
        'date_in',
        'deadline',
    ];

    protected $casts = [
        'date_in' => 'date',
        'deadline' => 'date',
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