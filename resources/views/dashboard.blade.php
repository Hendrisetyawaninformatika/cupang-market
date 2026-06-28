@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Mobile & Landscape Responsive */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1rem !important;
        }
        .dashboard-header h2 {
            font-size: 1.5rem;
        }
        .dashboard-header .d-flex.gap-2 {
            width: 100%;
            flex-wrap: wrap;
        }
        .dashboard-header .btn {
            flex: 1;
            min-width: 120px;
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .profile-card-inner {
            flex-direction: column !important;
            text-align: center;
            gap: 1rem !important;
        }
        .profile-card-inner .text-end {
            display: flex !important;
            justify-content: center;
            width: 100%;
            margin-top: 0.5rem;
        }

        .stat-card h3 {
            font-size: 1.5rem;
        }

        .product-table-img {
            width: 48px !important;
            height: 48px !important;
        }

        .quick-action {
            padding: 0.75rem !important;
        }
        .quick-action-icon {
            width: 36px !important;
            height: 36px !important;
            font-size: 0.9rem !important;
        }
    }

    @media (min-width: 576px) and (max-width: 991px) {
        /* Tablet landscape */
        .profile-card-inner {
            gap: 2rem !important;
        }
    }

    .profile-card-inner {
        flex-wrap: wrap;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
    }
    .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .quick-action-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .tips-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
    }

    .msg-item:hover {
        background-color: #f8f9fa !important;
    }

    .badge-unread {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
<meta name="viewport" content="width=1024">
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 dashboard-header">
        <h2 class="fw-bold m-0"><i class="fas fa-chart-line text-primary me-2"></i> Dashboard Penjual</h2>
        <div class="d-flex gap-2">
            @isset($unreadMessages)
            
                <span class="d-inline d-sm-none">Pesan</span>
                @if($unreadMessages > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-unread">
                        {{ $unreadMessages }}
                    </span>
                @endif
            </a>
            @endisset
            <a href="{{ route('products.create') }}" class="btn btn-success-custom">
                <i class="fas fa-plus me-1"></i> 
                <span class="d-none d-sm-inline">Tambah Produk</span>
                <span class="d-inline d-sm-none">Tambah</span>
            </a>
        </div>
    </div>

    <!-- Profil Penjual -->
    @isset($seller)
    <div class="card-custom p-3 p-md-4 mb-4">
        <div class="d-flex align-items-center gap-3 gap-md-4 profile-card-inner">
            <div class="position-relative flex-shrink-0">
                @if(!empty($seller['photo']))
                    <img src="{{ $seller['photo'] }}" class="rounded-circle" style="width:70px;height:70px;object-fit:cover;border:3px solid #0d6efd;">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:70px;height:70px;border:3px solid #0d6efd;">
                        <i class="fas fa-user fa-2x text-primary"></i>
                    </div>
                @endif
                <a href="{{ route('profile.edit', $seller['id']) }}" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle" style="width:26px;height:26px;padding:0;display:flex;align-items:center;justify-content:center;" title="Edit Foto">
                    <i class="fas fa-camera" style="font-size:10px;"></i>
                </a>
            </div>
            <div class="flex-grow-1 min-w-0">
                <h4 class="fw-bold mb-1 text-truncate">{{ $seller['name'] ?? 'Nama Penjual' }}</h4>
                <p class="text-muted mb-2 text-truncate"><i class="fab fa-whatsapp text-success me-2"></i>{{ $seller['whatsapp'] ?? 'Belum diatur' }}</p>
                <a href="{{ route('profile.edit', $seller['id']) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> Edit Profil
                </a>
            </div>
            <div class="d-flex gap-2 gap-md-3 w-100 w-md-auto justify-content-center justify-content-md-end">
                <div class="text-center px-2">
                    <h5 class="fw-bold text-primary mb-0">{{ $stats['totalProducts'] ?? 0 }}</h5>
                    <small class="text-muted">Produk</small>
                </div>
                <div class="text-center px-2">
                    <h5 class="fw-bold text-success mb-0">{{ $stats['soldProducts'] ?? 0 }}</h5>
                    <small class="text-muted">Terjual</small>
                </div>
                <div class="text-center px-2">
                    <h5 class="fw-bold text-warning mb-0">{{ $stats['rating'] ?? '0.0' }}</h5>
                    <small class="text-muted">Rating</small>
                </div>
            </div>
        </div>
    </div>
    @endisset

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card-custom stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-medium">Total Produk</p>
                        <h3 class="stat-value">{{ $stats['totalProducts'] ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card-custom stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-medium">Ikan Saya</p>
                        <h3 class="stat-value">{{ $stats['myProducts'] ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success rounded-3 p-2">
                        <i class="fas fa-fish"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card-custom stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-medium">Rata-rata Harga</p>
                        <h3 class="stat-value" style="font-size:1.4rem;">Rp {{ number_format($stats['avgPrice'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="stat-icon bg-purple bg-opacity-10 rounded-3 p-2" style="color:#8b5cf6;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Produk Saya -->
        <div class="col-lg-8">
            <div class="card-custom p-3 p-md-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-box text-primary me-2"></i> Produk Saya</h4>
                @if(isset($myProducts) && count($myProducts) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none d-sm-table-cell">Gambar</th>
                                    <th>Nama</th>
                                    <th class="d-none d-md-table-cell">Kategori</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myProducts as $id => $product)
                                <tr>
                                    <td class="d-none d-sm-table-cell">
                                        @if(!empty($product['imageBase64']))
                                            <img src="{{ $product['imageBase64'] }}" class="rounded-3 product-table-img" style="width:60px;height:60px;object-fit:cover;">
                                        @else
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center product-table-img" style="width:60px;height:60px;">
                                                <i class="fas fa-fish text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="fw-bold mb-0">{{ $product['name'] ?? 'Tanpa Nama' }}</h6>
                                        <p class="text-muted small mb-0 d-md-none">{{ $product['category'] ?? 'Umum' }}</p>
                                        <p class="text-muted small mb-0 d-none d-md-block">{{ Str::limit($product['description'] ?? '', 40) }}</p>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $product['category'] ?? 'Umum' }}</span>
                                    </td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('products.edit', $id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted">Belum ada produk</p>
                        <a href="{{ route('products.create') }}" class="btn btn-gradient mt-2">
                            <i class="fas fa-plus me-2"></i> Tambah Produk
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Kotak Masuk Mini -->
            <div class="card-custom p-3 p-md-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0 fs-6"><i class="fas fa-envelope text-info me-2"></i> Kotak Masuk</h4>
                </div>
                @if(isset($recentMessages) && count($recentMessages) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentMessages as $msg)
                        <a href="{{ route('inbox.show', $msg['id']) }}" class="list-group-item list-group-item-action px-0 py-2 border-0 border-bottom msg-item">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="mb-1 fw-bold text-truncate">{{ $msg['from'] ?? 'Pengirim' }}</h6>
                                        @if(isset($msg['read']) && !$msg['read'])
                                            <span class="badge bg-danger rounded-pill flex-shrink-0" style="font-size:9px;">Baru</span>
                                        @endif
                                    </div>
                                    <p class="mb-1 small text-muted text-truncate">{{ $msg['preview'] ?? '' }}</p>
                                </div>
                                <small class="text-muted flex-shrink-0 ms-2">{{ $msg['time'] ?? '' }}</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted opacity-25 mb-2"></i>
                        <p class="text-muted small mb-0">Belum ada pesan</p>
                    </div>
                @endif
            </div>

            <!-- Aksi Cepat -->
            <div class="card-custom p-3 p-md-4 mb-4">
                <h4 class="fw-bold mb-3 fs-6"><i class="fas fa-bolt text-warning me-2"></i> Aksi Cepat</h4>
                <div class="d-flex flex-column gap-2 gap-md-3">
                    <a href="{{ route('products.create') }}" class="quick-action bg-primary bg-opacity-10">
                        <div class="quick-action-icon bg-primary"><i class="fas fa-plus"></i></div>
                        <div class="min-w-0">
                            <p class="fw-bold mb-0 small">Tambah Produk</p>
                            <p class="text-muted mb-0" style="font-size:11px;">Jual ikan cupang baru</p>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="quick-action bg-purple bg-opacity-10">
                        <div class="quick-action-icon bg-purple" style="background:#8b5cf6;"><i class="fas fa-store"></i></div>
                        <div class="min-w-0">
                            <p class="fw-bold mb-0 small">Lihat Market</p>
                            <p class="text-muted mb-0" style="font-size:11px;">Jelajahi semua produk</p>
                        </div>
                    </a>
                    @isset($seller)
                    <a href="{{ route('profile.edit', $seller['id']) }}" class="quick-action bg-success bg-opacity-10">
                        <div class="quick-action-icon bg-success"><i class="fas fa-user-edit"></i></div>
                        <div class="min-w-0">
                            <p class="fw-bold mb-0 small">Edit Profil</p>
                            <p class="text-muted mb-0" style="font-size:11px;">Foto, nama & WhatsApp</p>
                        </div>
                    </a>
                    @endisset
                </div>
            </div>

            <!-- Tips -->
            <div class="tips-card">
                <h5 class="fw-bold mb-3 fs-6"><i class="fas fa-lightbulb text-warning me-2"></i> Tips Jualan</h5>
                <ul class="list-unstyled small opacity-90 mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Gunakan foto berkualitas tinggi</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Deskripsikan kondisi ikan dengan jelas</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tetapkan harga kompetitif</li>
                    <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Respons cepat ke pembeli</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
