<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->integer('qty');
            $table->foreignId('operator_id')->constrained('users')->cascadeOnDelete();
            $table->string('batch')->nullable();
            $table->date('production_date')->nullable();
            $table->date('date_in')->default(now());
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('incoming_items');
    }
};