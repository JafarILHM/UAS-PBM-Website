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
            // Menambahkan kolom item_batch_id
            $table->foreignId('item_batch_id')->nullable()->constrained('item_batches')->nullOnDelete()->after('item_id');

            // --- TAMBAHAN BARU: Menambahkan kolom supplier_id ---
            // Kita taruh nullable() agar aman, dan constrained ke tabel suppliers
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete()->after('operator_id');

            // Menghapus kolom lama yang tidak terpakai
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
            // Hapus constraint dan kolom batch/supplier saat rollback
            $table->dropForeign(['item_batch_id']);
            $table->dropColumn('item_batch_id');

            $table->dropForeign(['supplier_id']); // Hapus foreign key supplier
            $table->dropColumn('supplier_id');    // Hapus kolom supplier

            // Kembalikan kolom lama
            $table->string('batch')->nullable()->after('operator_id');
            $table->date('production_date')->nullable()->after('batch');
        });

        Schema::table('outgoing_items', function (Blueprint $table) {
            $table->dropForeign(['item_batch_id']);
            $table->dropColumn('item_batch_id');
        });
    }
};
