<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incoming_items', function (Blueprint $table) {
            $table->foreignId('item_batch_id')->nullable()->constrained('item_batches')->nullOnDelete()->after('item_id');
            $table->dropColumn(['batch', 'production_date']);
        });

        Schema::table('outgoing_items', function (Blueprint $table) {
            $table->foreignId('item_batch_id')->nullable()->constrained('item_batches')->nullOnDelete()->after('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_items', function (Blueprint $table) {
            $table->dropForeign(['item_batch_id']);
            $table->dropColumn('item_batch_id');
            $table->string('batch')->nullable()->after('operator_id');
            $table->date('production_date')->nullable()->after('batch');
        });

        Schema::table('outgoing_items', function (Blueprint $table) {
            $table->dropForeign(['item_batch_id']);
            $table->dropColumn('item_batch_id');
        });
    }
};