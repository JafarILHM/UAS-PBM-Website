<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitConversion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    // Unit CRUD operations
    public function index()
    {
        $units = Unit::paginate(10);
        return response()->json($units);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'symbol' => 'required|string|max:10|unique:units,symbol',
            'is_base_unit' => 'boolean',
        ]);

        $unit = Unit::create($validated);
        return response()->json($unit, 201);
    }

    public function show(Unit $unit)
    {
        return response()->json($unit);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('units')->ignore($unit->id)],
            'symbol' => ['required', 'string', 'max:10', Rule::unique('units')->ignore($unit->id)],
            'is_base_unit' => 'boolean',
        ]);

        $unit->update($validated);
        return response()->json($unit);
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json(null, 204);
    }

    // Unit Conversion CRUD operations
    public function indexConversions()
    {
        $conversions = UnitConversion::with(['fromUnit', 'toUnit', 'item'])->paginate(10);
        return response()->json($conversions);
    }

    public function storeConversion(Request $request)
    {
        $validated = $request->validate([
            'from_unit_id' => 'required|exists:units,id',
            'to_unit_id' => 'required|exists:units,id',
            'conversion_factor' => 'required|numeric|min:0.0001',
            'item_id' => 'nullable|exists:items,id',
        ]);

        $conversion = UnitConversion::create($validated);
        return response()->json($conversion, 201);
    }

    public function showConversion(UnitConversion $unitConversion)
    {
        return response()->json($unitConversion->load(['fromUnit', 'toUnit', 'item']));
    }

    public function updateConversion(Request $request, UnitConversion $unitConversion)
    {
        $validated = $request->validate([
            'from_unit_id' => ['required', 'exists:units,id'],
            'to_unit_id' => ['required', 'exists:units,id'],
            'conversion_factor' => 'required|numeric|min:0.0001',
            'item_id' => ['nullable', 'exists:items,id'],
        ]);

        $unitConversion->update($validated);
        return response()->json($unitConversion);
    }

    public function destroyConversion(UnitConversion $unitConversion)
    {
        $unitConversion->delete();
        return response()->json(null, 204);
    }
}