<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('outgoing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->integer('qty');
            $table->foreignId('operator_id')->constrained('users')->cascadeOnDelete();
            $table->string('purpose')->nullable();
            $table->date('date_out')->default(now());
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('outgoing_items');
    }
};
