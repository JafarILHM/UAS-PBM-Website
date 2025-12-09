<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('to_unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('conversion_factor', 8, 4); // e.g., 24.0000 for 1 carton = 24 pcs
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('cascade'); // For item-specific conversions
            $table->timestamps();

            $table->unique(['from_unit_id', 'to_unit_id', 'item_id']); // Ensure unique conversion for a given pair of units and item
        });
    }

    public function down(): void {
        Schema::dropIfExists('unit_conversions');
    }
};