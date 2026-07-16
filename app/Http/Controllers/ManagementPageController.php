<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ManagementPageController extends Controller
{
    public function users(): View
    {
        return $this->page('Kelola User', 'Atur akun pengguna dan hak akses aplikasi.');
    }

    public function fruits(): View
    {
        return $this->page('Kelola Data Buah', 'Kelola jenis buah, harga jual, dan informasi produk.');
    }

    public function suppliers(): View
    {
        return $this->page('Kelola Supplier', 'Kelola data pemasok buah untuk kebutuhan stok toko.');
    }

    public function stocks(): View
    {
        return $this->page('Kelola Stok', 'Pantau ketersediaan dan pergerakan stok buah.');
    }

    public function cashier(): View
    {
        return $this->page('Transaksi Penjualan / Kasir', 'Proses transaksi penjualan pelanggan di sini.');
    }

    public function transactions(): View
    {
        return $this->page('Kelola Data Transaksi', 'Lihat dan kelola riwayat transaksi penjualan.');
    }

    public function salesReport(): View
    {
        return $this->page('Laporan Penjualan', 'Tinjau ringkasan dan detail penjualan toko.');
    }

    public function profitLossReport(): View
    {
        return $this->page('Laporan Laba Rugi', 'Tinjau pendapatan, biaya, dan laba usaha toko.');
    }

    private function page(string $title, string $description): View
    {
        return view('pages.management', compact('title', 'description'));
    }
}
