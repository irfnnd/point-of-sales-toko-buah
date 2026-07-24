<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = Transaction::with('user')->orderBy('transaction_date', 'desc')->get();
        return view('reports.sales', compact('transactions'));
    }
}
