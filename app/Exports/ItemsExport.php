<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil data dari database
    */
    public function collection()
    {
        return Item::with(['category', 'unit'])->get();
    }

    /**
     * Judul Kolom di Excel (Header)
     */
    public function headings(): array
    {
        return [
            'SKU',
            'Nama Barang',
            'Kategori',
            'Stok Saat Ini',
            'Satuan',
            'Stok Minimum',
        ];
    }

    /**
     * Data yang akan dimasukkan ke baris Excel
     */
    public function map($item): array
    {
        return [
            $item->sku,
            $item->name,
            $item->category->name ?? '-',
            $item->stock,
            $item->unit->name ?? '-',
            $item->stock_minimum,
        ];
    }
}
