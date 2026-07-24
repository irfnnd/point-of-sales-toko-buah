@extends('adminlte::page')

@section('title', 'Kasir (Point of Sale)')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 fw-semibold">Kasir</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kasir</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <div class="row">
        {{-- ==================== BAGIAN KIRI: DAFTAR PRODUK ==================== --}}
        <div class="col-md-7 col-lg-8">
            <div class="card card-outline card-primary h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0"><i class="bi bi-basket2 me-2"></i>Pilih Buah</h3>
                        <div class="input-group input-group-sm w-50">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="search-product" placeholder="Cari nama atau kode buah...">
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light position-relative px-3 pt-3 pb-0 d-flex flex-column">
                    {{-- Container Scroll Vertical (Grid) --}}
                    <div id="product-scroll-container" style="overflow-y: auto; overflow-x: hidden; max-height: 55vh;" class="flex-grow-1 mb-2">
                        <div class="row" id="product-list">
                            @forelse($fruits as $fruit)
                                <div class="col-sm-6 col-md-4 col-xl-3 mb-3 product-item">
                                    <div class="card h-100 shadow-sm product-card border-0" 
                                         role="button" 
                                         onclick="addToCart({{ $fruit->id }}, '{{ addslashes($fruit->name) }}', {{ $fruit->selling_price }}, '{{ $fruit->unit }}')"
                                         data-name="{{ strtolower($fruit->name) }}" 
                                         data-code="{{ strtolower($fruit->code) }}">
                                        
                                        {{-- Placeholder untuk gambar buah, menggunakan div berwarna agar rapi --}}
                                        <div class="card-img-top bg-success bg-opacity-25 d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <i class="bi bi-apple fs-1 text-success opacity-50"></i>
                                        </div>
                                        
                                        <div class="card-body p-2 text-center">
                                            <span class="badge text-bg-secondary mb-1">{{ $fruit->code }}</span>
                                            <h6 class="card-title text-truncate w-100 mb-1 fw-bold" style="font-size: 0.9rem;">
                                                {{ $fruit->name }}
                                            </h6>
                                            <div class="text-primary fw-bold">
                                                Rp {{ number_format($fruit->selling_price, 0, ',', '.') }}
                                                <small class="text-muted fw-normal">/{{ $fruit->unit }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p>Data buah masih kosong. Tambahkan data buah terlebih dahulu.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== BAGIAN KANAN: KERANJANG (CART) ==================== --}}
        <div class="col-md-5 col-lg-4">
            <div class="card card-outline card-success h-100 d-flex flex-column">
                <div class="card-header">
                    <h3 class="card-title fw-bold"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool text-danger" onclick="clearCart()" title="Kosongkan Keranjang">
                            <i class="bi bi-trash"></i> Kosongkan
                        </button>
                    </div>
                </div>
                
                {{-- Daftar Item Keranjang --}}
                <div class="card-body p-0" style="overflow-y: auto; max-height: 40vh;" id="cart-items-container">
                    <table class="table table-striped table-hover m-0 align-middle" id="cart-table">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Produk</th>
                                <th style="width: 110px;" class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            {{-- Baris placeholder saat keranjang kosong --}}
                            <tr id="empty-cart-row">
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Keranjang masih kosong.<br><small>Pilih produk di sebelah kiri.</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Area Pembayaran (Footer) --}}
                <div class="card-footer bg-white border-top mt-auto p-3">
                    <form action="#" method="POST" id="form-transaction">
                        @csrf
                        {{-- Total Belanja --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0">Total Belanja</h5>
                            <h3 class="fw-bold text-success mb-0" id="display-total">Rp 0</h3>
                            <input type="hidden" name="total_amount" id="input-total-amount" value="0">
                        </div>
                        <hr>
                        
                        {{-- Input Pembayaran --}}
                        <div class="mb-3">
                            <label for="input-paid-amount" class="form-label fw-bold">Uang Bayar (Tunai)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text fw-bold">Rp</span>
                                <input type="number" class="form-control fw-bold text-end" id="input-paid-amount" name="paid_amount" placeholder="0" min="0" step="any" required>
                            </div>
                        </div>

                        {{-- Kembalian --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted fw-bold">Kembalian</span>
                            <h4 class="fw-bold text-primary mb-0" id="display-change">Rp 0</h4>
                            <input type="hidden" name="change_amount" id="input-change-amount" value="0">
                        </div>

                        {{-- Tombol Checkout --}}
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" id="btn-checkout" disabled>
                            <i class="bi bi-check-circle me-2"></i>PROSES TRANSAKSI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Styling untuk Kasir agar lebih interaktif */
    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #dee2e6 !important;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border-color: #28a745 !important;
    }
    /* Hide number input arrows for cleaner look */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@stop

@section('js')
<script>
    // State Keranjang Belanja
    let cart = {};
    let totalBelanja = 0;

    // Helper Format Rupiah
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // ========== Fitur Pencarian Produk ==========
    const searchInput = document.getElementById('search-product');
    const productItems = document.querySelectorAll('.product-item');

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        productItems.forEach(item => {
            const card = item.querySelector('.product-card');
            const name = card.getAttribute('data-name');
            const code = card.getAttribute('data-code');
            
            if (name.includes(keyword) || code.includes(keyword)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // ========== Fitur Tambah ke Keranjang ==========
    window.addToCart = function(id, name, price, unit) {
        if(cart[id]) {
            // Jika sudah ada, tambah quantity 1
            cart[id].qty += 1;
        } else {
            // Jika baru, inisiasi
            cart[id] = {
                id: id,
                name: name,
                price: parseFloat(price),
                qty: 1,
                unit: unit
            };
        }
        renderCart();
    };

    // ========== Update Quantity dari Input ==========
    window.updateQty = function(id, newQty) {
        const qty = parseFloat(newQty);
        if(isNaN(qty) || qty < 0) {
            return; // ignore invalid
        }
        if(qty === 0) {
            delete cart[id];
        } else {
            cart[id].qty = qty;
        }
        renderCart();
    }

    // ========== Hapus Item dari Keranjang ==========
    window.removeFromCart = function(id) {
        delete cart[id];
        renderCart();
    }

    // ========== Kosongkan Semua Keranjang ==========
    window.clearCart = function() {
        if(Object.keys(cart).length === 0) return;
        if(confirm('Anda yakin ingin mengosongkan keranjang belanja?')) {
            cart = {};
            renderCart();
        }
    }

    // ========== Render UI Keranjang & Kalkulasi Total ==========
    function renderCart() {
        const tbody = document.getElementById('cart-body');
        const emptyRow = document.getElementById('empty-cart-row');
        
        // Bersihkan row lama kecuali emptyRow (jika ada)
        tbody.innerHTML = '';
        totalBelanja = 0;

        const itemKeys = Object.keys(cart);
        
        if(itemKeys.length === 0) {
            tbody.innerHTML = `
                <tr id="empty-cart-row">
                    <td colspan="4" class="text-center py-4 text-muted">
                        Keranjang masih kosong.<br><small>Pilih produk di sebelah kiri.</small>
                    </td>
                </tr>
            `;
            updateTotalDisplay();
            return;
        }

        // Looping data cart
        itemKeys.forEach(id => {
            const item = cart[id];
            const subtotal = item.price * item.qty;
            totalBelanja += subtotal;

            const tr = document.createElement('tr');
            
            // Format number step based on unit. If 'kg' or 'gram', allow decimals.
            const step = (item.unit === 'kg' || item.unit === 'gram') ? '0.01' : '1';

            tr.innerHTML = `
                <td>
                    <div class="fw-bold">${item.name}</div>
                    <small class="text-muted">${formatRupiah(item.price)}/${item.unit}</small>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center" 
                           value="${item.qty}" min="0" step="${step}"
                           onchange="updateQty(${id}, this.value)"
                           onkeyup="if(event.key === 'Enter') updateQty(${id}, this.value)">
                </td>
                <td class="text-end fw-bold">
                    ${formatRupiah(subtotal)}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeFromCart(${id})">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        updateTotalDisplay();
    }

    function updateTotalDisplay() {
        // Update text
        document.getElementById('display-total').innerText = formatRupiah(totalBelanja);
        // Update hidden input
        document.getElementById('input-total-amount').value = totalBelanja;
        
        calculateChange();
    }

    // ========== Kalkulasi Kembalian (Change) ==========
    const inputPaid = document.getElementById('input-paid-amount');
    const displayChange = document.getElementById('display-change');
    const inputChangeAmount = document.getElementById('input-change-amount');
    const btnCheckout = document.getElementById('btn-checkout');

    inputPaid.addEventListener('input', calculateChange);

    function calculateChange() {
        const paid = parseFloat(inputPaid.value) || 0;
        const change = paid - totalBelanja;

        if (Object.keys(cart).length > 0 && paid >= totalBelanja && totalBelanja > 0) {
            // Cukup bayar
            displayChange.innerText = formatRupiah(change);
            displayChange.classList.remove('text-danger');
            displayChange.classList.add('text-primary');
            inputChangeAmount.value = change;
            
            // Enable checkout
            btnCheckout.disabled = false;
        } else {
            // Belum cukup / Kosong
            displayChange.innerText = 'Rp 0';
            displayChange.classList.remove('text-primary');
            displayChange.classList.add('text-danger');
            inputChangeAmount.value = 0;
            
            // Disable checkout
            btnCheckout.disabled = true;
        }
    }

    // Form Checkout Submission Intercept (Frontend Demo Only)
    document.getElementById('form-transaction').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Cek kembali jika cart kosong
        if(Object.keys(cart).length === 0) {
            alert('Keranjang belanja masih kosong!');
            return;
        }

        // Tampilkan notifikasi (karena backend store belum ada)
        alert('Transaksi Berhasil (Demo Frontend)!\nTotal: ' + formatRupiah(totalBelanja) + '\nKembalian: ' + formatRupiah(inputChangeAmount.value));
        
        // Reset 
        cart = {};
        inputPaid.value = '';
        renderCart();
    });

</script>
@stop
