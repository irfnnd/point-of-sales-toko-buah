<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FruitController extends Controller
{
    public function index(): View
    {
        return view('fruits.index');
    }
}
