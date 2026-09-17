@extends('adminlte::page')

@section('title', 'Kasir (Point of Sale)')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 fw-bold text-dark fs-4"></i>Kasir POS</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row g-3">
        {{-- ==================== BAGIAN KIRI: DAFTAR PRODUK ==================== --}}
        <div class="col-md-7 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-header bg-white border-bottom py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-1 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-grid-fill fs-6"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Pilih Produk Buah</h6>
                        </div>
                        <div class="input-group input-group-sm w-50">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-start-0 ps-0" id="search-product" placeholder="Cari nama atau kode buah...">
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light position-relative p-3 d-flex flex-column rounded-bottom-3">
                    {{-- Container Scroll Vertical (Grid) --}}
                    <div id="product-scroll-container" style="overflow-y: auto; overflow-x: hidden; max-height: 520px; min-height: 480px;" class="flex-grow-1">
                        <div class="row g-2" id="product-list">
                            @forelse($fruits as $fruit)
                                <div class="col-6 col-sm-6 col-md-4 col-xl-3 product-item">
                                    <div class="card h-100 fruit-card border-0 shadow-sm rounded-3 overflow-hidden position-relative bg-white" 
                                         role="button" 
                                         onclick="addToCart({{ $fruit->id }}, '{{ addslashes($fruit->name) }}', {{ $fruit->selling_price }}, '{{ $fruit->unit }}')"
                                         data-name="{{ strtolower($fruit->name) }}" 
                                         data-code="{{ strtolower($fruit->code) }}">
                                        
                                        {{-- Banner Header & Avatar Buah --}}
                                        <div class="card-img-top position-relative d-flex align-items-center justify-content-center py-3" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                            <span class="position-absolute top-0 start-0 m-2 badge bg-white text-secondary shadow-xs rounded-pill font-monospace" style="font-size: 0.65rem; border: 1px solid rgba(0,0,0,0.06);">
                                                {{ $fruit->code }}
                                            </span>
                                            <span class="position-absolute top-0 end-0 m-2 badge bg-success bg-opacity-20 text-success rounded-pill fw-bold" style="font-size: 0.65rem;">
                                                /{{ $fruit->unit }}
                                            </span>

                                            <div class="fruit-avatar shadow-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width: 50px; height: 50px;">
                                                <i class="bi bi-apple fs-2 text-success"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body p-2 d-flex flex-column justify-content-between text-center">
                                            <div>
                                                <h6 class="card-title text-truncate w-100 mb-1 fw-bold text-dark" style="font-size: 0.85rem;" title="{{ $fruit->name }}">
                                                    {{ $fruit->name }}
                                                </h6>
                                                <div class="bg-light rounded-2 py-1 px-2 my-1">
                                                    <span class="text-success fw-bold" style="font-size: 0.88rem;">
                                                        Rp {{ number_format($fruit->selling_price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill mt-1 py-1 w-100 fw-bold btn-add-cart" style="font-size: 0.74rem;">
                                                <i class="bi bi-plus-circle-fill me-1"></i>Tambah
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    <p class="mb-0">Data buah masih kosong. Tambahkan data buah terlebih dahulu.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== BAGIAN KANAN: KERANJANG (CART) ==================== --}}
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 d-flex flex-column bg-white" style="height: 580px;">
                <div class="card-header bg-white border-bottom py-2 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-2 p-1 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bi bi-cart3 fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Keranjang Belanja</h6>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill ms-2" id="cart-count-badge" style="font-size: 0.7rem;">0 item</span>
                    </div>
                    <button type="button" class="btn btn-link text-danger text-decoration-none p-0" style="font-size: 0.75rem;" onclick="clearCart()" title="Kosongkan Keranjang">
                        <i class="bi bi-trash me-1"></i>Kosongkan
                    </button>
                </div>
                
                {{-- Daftar Item Keranjang --}}
                <div class="card-body p-0 flex-grow-1" style="overflow-y: auto; height: 240px; max-height: 240px;" id="cart-items-container">
                    <table class="table table-sm table-hover m-0 align-middle" id="cart-table" style="font-size: 0.8rem;">
                        <thead class="bg-light text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <tr>
                                <th class="ps-3 py-2">Produk</th>
                                <th style="width: 95px;" class="text-center py-2">Jumlah</th>
                                <th class="text-end py-2">Subtotal</th>
                                <th style="width: 32px;" class="pe-2 py-2"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <tr id="empty-cart-row">
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="opacity-50 mb-2"><i class="bi bi-cart-x fs-1 text-muted"></i></div>
                                    <span style="font-size: 0.82rem;">Keranjang masih kosong</span><br>
                                    <small class="text-muted" style="font-size: 0.73rem;">Klik buah di sebelah kiri untuk menambah.</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Area Pembayaran (Footer) --}}
                <div class="card-footer bg-light border-top p-3 mt-auto rounded-bottom-3">
                    <form action="#" method="POST" id="form-transaction">
                        @csrf
                        {{-- Total Belanja Banner --}}
                        <div class="bg-success bg-opacity-10 rounded-3 p-2 px-3 mb-2 d-flex justify-content-between align-items-center border border-success border-opacity-25">
                            <div>
                                <small class="text-success fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">Total Transaksi</small>
                                <span class="fw-bold text-muted" style="font-size: 0.75rem;" id="display-item-count">0 Jenis Produk</span>
                            </div>
                            <h3 class="fw-extrabold text-success mb-0" style="font-size: 1.25rem; font-weight: 800;" id="display-total">Rp 0</h3>
                            <input type="hidden" name="total_amount" id="input-total-amount" value="0">
                        </div>

                        {{-- Preset Tombol Uang Pas --}}
                        <div class="d-flex gap-1 mb-2">
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 rounded-2" style="font-size: 0.7rem;" onclick="setQuickPay('pas')">Uang Pas</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 rounded-2" style="font-size: 0.7rem;" onclick="setQuickPay(20000)">20rb</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 rounded-2" style="font-size: 0.7rem;" onclick="setQuickPay(50000)">50rb</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-fill py-1 rounded-2" style="font-size: 0.7rem;" onclick="setQuickPay(100000)">100rb</button>
                        </div>

                        {{-- Input Pembayaran --}}
                        <div class="mb-2">
                            <label for="input-paid-amount" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.78rem;">Uang Bayar (Tunai)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white fw-bold text-muted" style="font-size: 0.78rem;">Rp</span>
                                <input type="number" class="form-control fw-bold text-end" style="font-size: 0.88rem;" id="input-paid-amount" name="paid_amount" placeholder="0" min="0" step="any" required>
                            </div>
                        </div>

                        {{-- Kembalian --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                            <span class="text-muted fw-semibold" style="font-size: 0.78rem;">Kembalian</span>
                            <h5 class="fw-bold text-primary mb-0" style="font-size: 0.95rem;" id="display-change">Rp 0</h5>
                            <input type="hidden" name="change_amount" id="input-change-amount" value="0">
                        </div>

                        {{-- Tombol Checkout --}}
                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold py-2 shadow-sm rounded-2 text-uppercase" style="font-size: 0.82rem; letter-spacing: 0.5px;" id="btn-checkout" disabled>
                            <i class="bi bi-check-circle-fill me-1"></i> PROSES TRANSAKSI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Styling Card Buah (Kasir POS) */
    .fruit-card {
        transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0, 0, 0, 0.07) !important;
    }
    .fruit-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.75rem 1.5rem rgba(40, 167, 69, 0.18) !important;
        border-color: rgba(40, 167, 69, 0.45) !important;
    }
    .fruit-card:hover .fruit-avatar {
        transform: scale(1.12) rotate(4deg);
        transition: transform 0.25s ease;
    }
    .fruit-card:hover .btn-add-cart {
        background-color: #28a745 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
    }
    .fruit-avatar {
        transition: transform 0.25s ease;
    }
    /* Custom Scrollbar Styling */
    #product-scroll-container::-webkit-scrollbar,
    #cart-items-container::-webkit-scrollbar {
        width: 5px;
    }
    #product-scroll-container::-webkit-scrollbar-track,
    #cart-items-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    #product-scroll-container::-webkit-scrollbar-thumb,
    #cart-items-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    #product-scroll-container::-webkit-scrollbar-thumb:hover,
    #cart-items-container::-webkit-scrollbar-thumb:hover {
        background: #28a745;
    }
    /* Hide number input spinners */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Sembunyikan Footer AdminLTE Khusus di Halaman Kasir */
    .main-footer, .app-footer, footer.app-footer, footer.main-footer {
        display: none !important;
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
            cart[id].qty += 1;
        } else {
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

    // ========== Ubah Qty via Tombol + / - ==========
    window.changeQty = function(id, delta) {
        if(!cart[id]) return;
        let newQty = cart[id].qty + delta;
        if (newQty <= 0) {
            delete cart[id];
        } else {
            cart[id].qty = newQty;
        }
        renderCart();
    };

    // ========== Update Quantity dari Input ==========
    window.updateQty = function(id, newQty) {
        const qty = parseFloat(newQty);
        if(isNaN(qty) || qty < 0) {
            return;
        }
        if(qty === 0) {
            delete cart[id];
        } else {
            cart[id].qty = qty;
        }
        renderCart();
    };

    // ========== Fitur Uang Pas & Quick Pay ==========
    window.setQuickPay = function(val) {
        if (val === 'pas') {
            inputPaid.value = totalBelanja;
        } else {
            inputPaid.value = val;
        }
        calculateChange();
    };

    // ========== Hapus Item dari Keranjang ==========
    window.removeFromCart = function(id) {
        delete cart[id];
        renderCart();
    };

    // ========== Kosongkan Semua Keranjang ==========
    window.clearCart = function() {
        if(Object.keys(cart).length === 0) return;
        if(confirm('Anda yakin ingin mengosongkan keranjang belanja?')) {
            cart = {};
            renderCart();
        }
    };

    // ========== Render UI Keranjang & Kalkulasi Total ==========
    function renderCart() {
        const tbody = document.getElementById('cart-body');
        const cartCountBadge = document.getElementById('cart-count-badge');
        const displayItemCount = document.getElementById('display-item-count');
        
        tbody.innerHTML = '';
        totalBelanja = 0;

        const itemKeys = Object.keys(cart);
        
        // Update total jenis item
        cartCountBadge.innerText = itemKeys.length + ' item';
        displayItemCount.innerText = itemKeys.length + ' Jenis Produk';

        if(itemKeys.length === 0) {
            tbody.innerHTML = `
                <tr id="empty-cart-row">
                    <td colspan="4" class="text-center py-5 text-muted">
                        <div class="opacity-50 mb-2"><i class="bi bi-cart-x fs-1 text-muted"></i></div>
                        <span style="font-size: 0.82rem;">Keranjang masih kosong</span><br>
                        <small class="text-muted" style="font-size: 0.73rem;">Klik buah di sebelah kiri untuk menambah.</small>
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
            const step = (item.unit === 'kg' || item.unit === 'gram') ? '0.01' : '1';

            tr.innerHTML = `
                <td class="ps-3">
                    <div class="fw-bold text-truncate text-dark" style="max-width: 95px; font-size: 0.8rem;" title="${item.name}">${item.name}</div>
                    <small class="text-muted d-block" style="font-size: 0.7rem;">${formatRupiah(item.price)}/${item.unit}</small>
                </td>
                <td>
                    <div class="input-group input-group-sm flex-nowrap" style="width: 90px;">
                        <button type="button" class="btn btn-outline-secondary px-1 py-0" style="font-size: 0.7rem;" onclick="changeQty(${id}, -1)">-</button>
                        <input type="number" class="form-control form-control-sm text-center px-0 fw-semibold" 
                               style="font-size: 0.78rem; height: 26px;"
                               value="${item.qty}" min="0" step="${step}"
                               onchange="updateQty(${id}, this.value)"
                               onkeyup="if(event.key === 'Enter') updateQty(${id}, this.value)">
                        <button type="button" class="btn btn-outline-secondary px-1 py-0" style="font-size: 0.7rem;" onclick="changeQty(${id}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end fw-bold text-dark" style="font-size: 0.8rem;">
                    ${formatRupiah(subtotal)}
                </td>
                <td class="text-center pe-2">
                    <button type="button" class="btn btn-sm text-danger opacity-50 hover-opacity-100 border-0 p-0" style="font-size: 0.85rem;" onclick="removeFromCart(${id})" title="Hapus">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        updateTotalDisplay();
    }

    function updateTotalDisplay() {
        document.getElementById('display-total').innerText = formatRupiah(totalBelanja);
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
            displayChange.innerText = formatRupiah(change);
            displayChange.classList.remove('text-danger');
            displayChange.classList.add('text-primary');
            inputChangeAmount.value = change;
            btnCheckout.disabled = false;
        } else {
            displayChange.innerText = 'Rp 0';
            displayChange.classList.remove('text-primary');
            displayChange.classList.add('text-danger');
            inputChangeAmount.value = 0;
            btnCheckout.disabled = true;
        }
    }

    // Form Checkout Submission Intercept
    document.getElementById('form-transaction').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if(Object.keys(cart).length === 0) {
            alert('Keranjang belanja masih kosong!');
            return;
        }

        alert('Transaksi Berhasil!\nTotal: ' + formatRupiah(totalBelanja) + '\nKembalian: ' + formatRupiah(inputChangeAmount.value));
        
        cart = {};
        inputPaid.value = '';
        renderCart();
    });
</script>
@stop
