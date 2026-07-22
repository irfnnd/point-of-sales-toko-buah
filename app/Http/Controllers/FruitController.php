<?php

namespace App\Http\Controllers;

use App\Models\Fruit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FruitController extends Controller
{
    public function index(): View
    {
        $fruits = Fruit::latest()->get();

        return view('fruits.index', compact('fruits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:fruits,code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Fruit::create($validated);

        return redirect()->route('data.fruits')->with('success', 'Data buah berhasil ditambahkan.');
    }

    public function update(Request $request, Fruit $fruit): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:fruits,code,' . $fruit->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $fruit->update($validated);

        return redirect()->route('data.fruits')->with('success', 'Data buah berhasil diperbarui.');
    }

    public function destroy(Fruit $fruit): RedirectResponse
    {
        $fruit->delete();

        return redirect()->route('data.fruits')->with('success', 'Data buah berhasil dihapus.');
    }
}
