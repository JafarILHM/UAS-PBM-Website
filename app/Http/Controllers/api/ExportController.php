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
        return Excel::download(new ItemsExport, 'items.xlsx');
    }

    public function exportTransactions()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}
