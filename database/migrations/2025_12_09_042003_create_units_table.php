<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('symbol')->unique();
            $table->boolean('is_base_unit')->default(false); // e.g., 'pcs' for an item
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('units');
    }
};