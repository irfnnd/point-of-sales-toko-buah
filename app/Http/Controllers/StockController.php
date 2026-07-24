<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Fruit;
use App\Models\Supplier;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $stocks = Stock::with(['fruit', 'supplier'])->orderBy('recorded_at', 'desc')->get();
        $fruits = Fruit::all();
        $suppliers = Supplier::all();
        
        return view('stocks.index', compact('stocks', 'fruits', 'suppliers'));
    }
}
