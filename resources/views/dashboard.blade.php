@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-chart-line text-primary me-2"></i> Dashboard Penjual</h2>
        <a href="{{ route('products.create') }}" class="btn btn-success-custom"><i class="fas fa-plus me-2"></i> Tambah Produk</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card-custom stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-medium">Total Produk</p>
                        <h3 class="stat-value">{{ $stats['totalProducts'] }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-box"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-medium">Ikan Saya</p>
                        <h3 class="stat-value">{{ $stats['myProducts'] }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-fish"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-medium">Rata-rata Harga</p>
                        <h3 class="stat-value">Rp {{ number_format($stats['avgPrice'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="stat-icon bg-purple bg-opacity-10" style="color:#8b5cf6;"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom p-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-box text-primary me-2"></i> Produk Saya</h4>
                @if(count($myProducts) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @foreach($myProducts as $id => $product)
                                <tr>
                                    <td>
                                        @if(!empty($product['imageBase64']))
                                            <img src="{{ $product['imageBase64'] }}" class="rounded-3" style="width:60px;height:60px;object-fit:cover;">
                                        @else
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width:60px;height:60px;"><i class="fas fa-fish text-muted"></i></div>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="fw-bold mb-0">{{ $product['name'] }}</h6>
                                        <p class="text-muted small mb-0">{{ Str::limit($product['description'] ?? '', 50) }}</p>
                                    </td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $product['category'] }}</span></td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('products.edit', $id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('products.destroy', $id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
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
                        <a href="{{ route('products.create') }}" class="btn btn-gradient mt-2"><i class="fas fa-plus me-2"></i> Tambah Produk</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom p-4 mb-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-bolt text-warning me-2"></i> Aksi Cepat</h4>
                <div class="d-flex flex-column gap-3">
                    <a href="{{ route('products.create') }}" class="quick-action bg-primary bg-opacity-10">
                        <div class="quick-action-icon bg-primary"><i class="fas fa-plus"></i></div>
                        <div><p class="fw-bold mb-0 small">Tambah Produk</p><p class="text-muted mb-0" style="font-size:12px;">Jual ikan cupang baru</p></div>
                    </a>
                    <a href="{{ route('home') }}" class="quick-action bg-purple bg-opacity-10">
                        <div class="quick-action-icon bg-purple" style="background:#8b5cf6;"><i class="fas fa-store"></i></div>
                        <div><p class="fw-bold mb-0 small">Lihat Market</p><p class="text-muted mb-0" style="font-size:12px;">Jelajahi semua produk</p></div>
                    </a>
                </div>
            </div>

            <div class="tips-card">
                <h5 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning me-2"></i> Tips Jualan</h5>
                <ul class="list-unstyled small opacity-90">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Gunakan foto berkualitas tinggi</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Deskripsikan kondisi ikan dengan jelas</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tetapkan harga kompetitif</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i>Respons cepat ke pembeli</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection