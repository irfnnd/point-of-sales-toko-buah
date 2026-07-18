<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProfitLossReportController extends Controller
{
    public function index(): View
    {
        return view('reports.profit-loss');
    }
}
