<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('items', function (Blueprint $table) {
            // Drop the old 'unit' string column
            $table->dropColumn('unit');
            // Add the new 'unit_id' foreign key
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete()->after('stock_minimum');
        });
    }

    public function down(): void {
        Schema::table('items', function (Blueprint $table) {
            // Revert changes: drop 'unit_id' and add back 'unit' string column
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            $table->string('unit')->default('pcs')->after('stock_minimum'); // Re-add the column as it was
        });
    }
};