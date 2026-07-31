<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Fruit;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(): View
    {
        $stocks = Stock::with(['fruit', 'supplier'])->orderBy('recorded_at', 'desc')->get();
        $fruits = Fruit::all();
        $suppliers = Supplier::all();
        
        return view('stocks.index', compact('stocks', 'fruits', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fruit_id' => 'required|exists:fruits,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|gt:0',
            'unit_price' => 'nullable|numeric|min:0',
            'recorded_at' => 'required|date',
            'expired_at' => 'nullable|date',
            'note' => 'nullable|string|max:1000',
        ]);

        if (! isset($validated['unit_price'])) {
            $validated['unit_price'] = 0;
        }

        if ($validated['type'] === 'in') {
            if (empty($validated['expired_at'])) {
                $fruit = Fruit::find($validated['fruit_id']);
                if ($fruit && $fruit->shelf_life_days) {
                    $recordedAt = Carbon::parse($validated['recorded_at']);
                    $validated['expired_at'] = $recordedAt->copy()->addDays($fruit->shelf_life_days)->format('Y-m-d H:i:s');
                }
            }
        } else {
            $validated['expired_at'] = null;
        }

        Stock::create($validated);

        return redirect()->route('data.stocks')->with('success', 'Data transaksi stok berhasil ditambahkan.');
    }
}
