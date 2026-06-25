@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="container py-4">
    <!-- Hero -->
    <div class="hero-section">
        <h1 class="hero-title">Temukan Ikan Cupang <span>Berkualitas</span></h1>
        <p class="hero-text">Ribuan koleksi ikan cupang dari breeder terpercaya. Bergaransi hidup sampai tujuan!</p>
        <div class="d-flex gap-3 flex-wrap">
            <a href="#products" class="btn btn-light fw-bold"><i class="fas fa-search me-2"></i> Jelajahi</a>
            @if(session('firebase_user'))
                <a href="{{ route('products.create') }}" class="btn btn-success-custom fw-bold"><i class="fas fa-plus me-2"></i> Jual Cupang</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-success-custom fw-bold"><i class="fas fa-plus me-2"></i> Jual Cupang</a>
            @endif
        </div>
    </div>

    <!-- Search -->
    <div class="search-bar mb-4">
        <form action="{{ route('home') }}" method="GET">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" class="form-control form-control-custom ps-5" placeholder="Cari ikan cupang..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-control-custom">
                        <option value="">Semua Kategori</option>
                        <option value="Halfmoon" {{ request('category') == 'Halfmoon' ? 'selected' : '' }}>Halfmoon</option>
                        <option value="Plakat" {{ request('category') == 'Plakat' ? 'selected' : '' }}>Plakat</option>
                        <option value="Crowntail" {{ request('category') == 'Crowntail' ? 'selected' : '' }}>Crowntail</option>
                        <option value="Double Tail" {{ request('category') == 'Double Tail' ? 'selected' : '' }}>Double Tail</option>
                        <option value="Super Delta" {{ request('category') == 'Super Delta' ? 'selected' : '' }}>Super Delta</option>
                        <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-gradient w-100"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories - IMPROVED MOBILE & ICONS -->
    <h3 class="section-title"><i class="fas fa-th-large text-primary"></i> Kategori</h3>
    <div class="row g-3 mb-5 category-row">
        @php
            $categories = [
                ['name' => 'Halfmoon', 'icon' => 'fa-water', 'color' => 'bg-danger', 'desc' => 'Sirip lebar'],
                ['name' => 'Plakat', 'icon' => 'fa-fish', 'color' => 'bg-primary', 'desc' => 'Sirip pendek'],
                ['name' => 'Crowntail', 'icon' => 'fa-crown', 'color' => 'bg-purple', 'desc' => 'Sirip berduri'],
                ['name' => 'Double Tail', 'icon' => 'fa-code-branch', 'color' => 'bg-success', 'desc' => 'Dua ekor'],
                ['name' => 'Super Delta', 'icon' => 'fa-star', 'color' => 'bg-warning', 'desc' => 'Delta besar'],
                ['name' => 'Lainnya', 'icon' => 'fa-ellipsis-h', 'color' => 'bg-secondary', 'desc' => 'Jenis lain'],
            ];
        @endphp
        @foreach($categories as $cat)
        <div class="col-4 col-md-4 col-lg-2">
            <a href="{{ route('home', ['category' => $cat['name']]) }}" class="category-card">
                <div class="category-icon {{ $cat['color'] }}"><i class="fas {{ $cat['icon'] }}"></i></div>
                <h5 class="fw-bold small category-name">{{ $cat['name'] }}</h5>
                <span class="category-desc d-none d-md-block">{{ $cat['desc'] }}</span>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Filter Pills -->
    <div class="filter-pills">
        <a href="{{ route('home') }}" class="filter-pill {{ !request('category') ? 'active' : '' }}">Semua</a>
        <a href="{{ route('home', ['category' => 'Halfmoon']) }}" class="filter-pill {{ request('category') == 'Halfmoon' ? 'active' : '' }}">Halfmoon</a>
        <a href="{{ route('home', ['category' => 'Plakat']) }}" class="filter-pill {{ request('category') == 'Plakat' ? 'active' : '' }}">Plakat</a>
        <a href="{{ route('home', ['category' => 'Crowntail']) }}" class="filter-pill {{ request('category') == 'Crowntail' ? 'active' : '' }}">Crowntail</a>
        <a href="{{ route('home', ['category' => 'Double Tail']) }}" class="filter-pill {{ request('category') == 'Double Tail' ? 'active' : '' }}">Double Tail</a>
        <a href="{{ route('home', ['category' => 'Super Delta']) }}" class="filter-pill {{ request('category') == 'Super Delta' ? 'active' : '' }}">Super Delta</a>
        <a href="{{ route('home', ['category' => 'Lainnya']) }}" class="filter-pill {{ request('category') == 'Lainnya' ? 'active' : '' }}">Lainnya</a>
    </div>

    <!-- Products -->
    <div class="d-flex justify-content-between align-items-center mb-4" id="products">
        <h3 class="section-title mb-0"><i class="fas fa-fire text-danger"></i> Daftar Ikan Cupang</h3>
        @if(session('firebase_user'))
            <a href="{{ route('products.create') }}" class="btn btn-gradient"><i class="fas fa-plus me-2"></i> Tambah Ikan</a>
        @endif
    </div>

    @if(count($products) > 0)
        <div class="row g-4">
            @foreach($products as $id => $product)
            <div class="col-6 col-lg-3">
                <div class="product-card">
                    <div class="product-img-wrapper">
                        @if(!empty($product['imageBase64']))
                            <img src="{{ $product['imageBase64'] }}" class="product-img" alt="{{ $product['name'] }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-fish fa-3x text-muted opacity-25"></i>
                            </div>
                        @endif
                        @if(session('firebase_user') && ($product['sellerId'] ?? '') === session('firebase_user.uid'))
                            <span class="product-badge badge-mine">Milik Saya</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <span class="product-category">{{ $product['category'] ?? 'Umum' }}</span>
                        <h5 class="product-name">{{ $product['name'] ?? 'Produk' }}</h5>
                        <p class="product-desc">{{ $product['description'] ?? '' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="product-seller"><i class="fas fa-user-circle me-1"></i> {{ $product['sellerName'] ?? 'Anonim' }}</div>
                        @if(session('firebase_user') && ($product['sellerId'] ?? '') === session('firebase_user.uid'))
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('products.edit', $id) }}" class="btn btn-sm btn-warning text-white flex-fill"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('products.destroy', $id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-fish"></i>
            <h4 class="fw-bold text-muted">Belum ada ikan</h4>
            <p class="text-muted">Jadilah yang pertama menjual ikan cupang!</p>
            @if(session('firebase_user'))
                <a href="{{ route('products.create') }}" class="btn btn-gradient mt-3"><i class="fas fa-plus me-2"></i> Tambah Ikan</a>
            @endif
        </div>
    @endif

    <!-- Why Choose Us -->
    <div class="card-custom p-5 mt-5">
        <h3 class="text-center fw-bold mb-5">Mengapa Cupang Market?</h3>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto"><i class="fas fa-shield-alt"></i></div>
                <h5 class="fw-bold">Garansi Hidup</h5>
                <p class="text-muted small">Jaminan ikan hidup sampai tujuan</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto"><i class="fas fa-truck-fast"></i></div>
                <h5 class="fw-bold">Pengiriman Aman</h5>
                <p class="text-muted small">Pengemasan khusus dengan oksigen</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="stat-icon bg-purple bg-opacity-10 mx-auto" style="color:#8b5cf6;"><i class="fas fa-medal"></i></div>
                <h5 class="fw-bold">Breeder Terpercaya</h5>
                <p class="text-muted small">Penjual terverifikasi</p>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* ============================================
   CATEGORY CARDS - MOBILE OPTIMIZED
   ============================================ */

.category-row {
    /* Allow horizontal scroll on very small screens if needed */
    flex-wrap: wrap;
}

.category-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem 0.5rem;
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid #e9ecef;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    height: 100%;
    min-height: 120px;
    justify-content: center;
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #dee2e6;
}

.category-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    margin-bottom: 0.75rem;
    transition: transform 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

.category-name {
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.category-desc {
    font-size: 0.65rem;
    color: #6c757d;
    line-height: 1.2;
}

/* Mobile adjustments */
@media (max-width: 576px) {
    .category-card {
        padding: 0.75rem 0.25rem;
        min-height: 100px;
        border-radius: 12px;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .category-name {
        font-size: 0.7rem;
    }
}

/* Tablet adjustments */
@media (min-width: 577px) and (max-width: 991px) {
    .category-card {
        padding: 1rem 0.75rem;
    }

    .category-icon {
        width: 52px;
        height: 52px;
        font-size: 1.4rem;
    }
}

/* ============================================
   FILTER PILLS - MOBILE SCROLLABLE
   ============================================ */
.filter-pills {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE 10+ */
}

.filter-pills::-webkit-scrollbar { 
    display: none; /* Chrome/Safari */
}

.filter-pill {
    white-space: nowrap;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #495057;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.filter-pill:hover {
    background: #e9ecef;
    color: #212529;
}

.filter-pill.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

/* ============================================
   PRODUCT CARDS - MOBILE OPTIMIZED
   ============================================ */
@media (max-width: 576px) {
    .product-card {
        border-radius: 12px;
    }

    .product-img-wrapper {
        height: 140px;
    }

    .product-name {
        font-size: 0.9rem;
    }

    .product-price {
        font-size: 0.85rem;
    }

    .product-seller {
        font-size: 0.75rem;
    }

    .hero-title {
        font-size: 1.75rem;
    }

    .hero-text {
        font-size: 0.9rem;
    }

    .search-bar {
        padding: 1rem;
    }

    .section-title {
        font-size: 1.1rem;
    }
}

/* ============================================
   UTILITY COLORS
   ============================================ */
.bg-purple {
    background-color: #8b5cf6 !important;
}

.text-purple {
    color: #8b5cf6 !important;
}
</style>