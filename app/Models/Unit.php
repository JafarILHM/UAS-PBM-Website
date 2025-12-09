<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'is_base_unit',
    ];

    protected $casts = [
        'is_base_unit' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function fromUnitConversions()
    {
        return $this->hasMany(UnitConversion::class, 'from_unit_id');
    }

    public function toUnitConversions()
    {
        return $this->hasMany(UnitConversion::class, 'to_unit_id');
    }
}