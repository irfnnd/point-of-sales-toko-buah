<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();
        return view('suppliers.index', compact('suppliers'));
    }
}
