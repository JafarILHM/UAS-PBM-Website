<?php

namespace App\Exports;

use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $incoming = IncomingItem::with('item')->get();
        $outgoing = OutgoingItem::with('item')->get();

        $transactions = $incoming->map(function ($item) {
            return [
                'type' => 'in',
                'item_name' => $item->item->name,
                'quantity' => $item->qty,
                'created_at' => $item->date_in ?? $item->created_at,
            ];
        });

        $transactions = $transactions->concat($outgoing->map(function ($item) {
            return [
                'type' => 'out',
                'item_name' => $item->item->name,
                'quantity' => $item->qty,
                'created_at' => $item->date_out ?? $item->created_at,
            ];
        }));

        return $transactions->sortByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Type',
            'Quantity',
            'Date',
        ];
    }

    public function map($transaction): array
    {
        $date = $transaction['created_at'] instanceof \DateTime ? $transaction['created_at'] : Carbon::parse($transaction['created_at']);
        return [
            $transaction['item_name'],
            $transaction['type'] == 'in' ? 'Incoming' : 'Outgoing',
            $transaction['quantity'],
            $date->format('d M Y, H:i'),
        ];
    }
}
