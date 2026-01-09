<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Exports\ItemsExport;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportItems()
    {
        $fileName = 'items_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new ItemsExport, $fileName);
    }

    public function exportTransactions()
    {
        $fileName = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new TransactionsExport, $fileName);
    }
}
