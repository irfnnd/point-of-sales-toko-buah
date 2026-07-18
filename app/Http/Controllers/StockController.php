<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        return view('stocks.index');
    }
}
