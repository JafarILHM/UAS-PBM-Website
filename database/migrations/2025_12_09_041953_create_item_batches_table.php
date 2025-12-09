<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('item_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('batch_number');
            $table->date('production_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['item_id', 'batch_number']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('item_batches');
    }
};