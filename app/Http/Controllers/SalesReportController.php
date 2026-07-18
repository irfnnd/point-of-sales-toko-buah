<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SalesReportController extends Controller
{
    public function index(): View
    {
        return view('reports.sales');
    }
}
