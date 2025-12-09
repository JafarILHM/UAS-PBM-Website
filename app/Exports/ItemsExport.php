<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'Barcode Base64',
            'Name',
            'Description',
            'Price',
            'Stock',
            'Category ID',
            'Supplier ID',
            'Unit ID',
            'Created At',
            'Updated At',
        ];
    }
}
