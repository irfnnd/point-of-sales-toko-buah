<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = Transaction::with('user')->orderBy('transaction_date', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }
}
