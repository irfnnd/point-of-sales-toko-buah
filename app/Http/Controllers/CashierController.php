<?php

namespace App\Http\Controllers;

use App\Models\Fruit;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(): View
    {
        // Ambil data asli dari database
        $fruits = Fruit::orderBy('name')->get();
        
        // JIKA KOSONG, buatkan dummy data khusus untuk testing frontend kasir
        if ($fruits->isEmpty()) {
            $fruits = collect([
                (object) ['id' => 901, 'code' => 'APL-01', 'name' => 'Apel Fuji Super', 'selling_price' => 35000, 'unit' => 'kg'],
                (object) ['id' => 902, 'code' => 'JRK-01', 'name' => 'Jeruk Mandarin', 'selling_price' => 28000, 'unit' => 'kg'],
                (object) ['id' => 903, 'code' => 'MNG-01', 'name' => 'Mangga Harum Manis', 'selling_price' => 15000, 'unit' => 'kg'],
                (object) ['id' => 904, 'code' => 'PIS-01', 'name' => 'Pisang Sunpride', 'selling_price' => 25000, 'unit' => 'sisir'],
                (object) ['id' => 905, 'code' => 'ANG-01', 'name' => 'Anggur Merah Tanpa Biji', 'selling_price' => 65000, 'unit' => 'kg'],
                (object) ['id' => 906, 'code' => 'SMK-01', 'name' => 'Semangka Merah', 'selling_price' => 30000, 'unit' => 'pcs'],
                (object) ['id' => 907, 'code' => 'MLN-01', 'name' => 'Melon Sky', 'selling_price' => 20000, 'unit' => 'pcs'],
                (object) ['id' => 908, 'code' => 'STR-01', 'name' => 'Strawberry Pack Besar', 'selling_price' => 35000, 'unit' => 'pack'],
            ]);
        }

        return view('cashier.index', compact('fruits'));
    }
}
