@extends('layouts.app')

@section('title', 'Kotak Masuk')

@section('content')
<style>
    @media (max-width: 768px) {
        .inbox-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1rem !important;
        }
        .inbox-header h2 {
            font-size: 1.5rem;
        }
        .inbox-item {
            padding: 1rem !important;
        }
    }
</style>
<meta name="viewport" content="width=1024">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 inbox-header">
        <h2 class="fw-bold m-0"><i class="fas fa-envelope text-info me-2"></i> Kotak Masuk</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('inbox') }}" class="btn btn-outline-primary {{ request('filter', 'all') == 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('filter', 'unread') }}" class="btn btn-outline-primary {{ request('filter') == 'unread' ? 'active' : '' }}">Belum Dibaca</a>
            <a href="{{ route('inbox') }}?filter=read" class="btn btn-outline-primary {{ request('filter') == 'read' ? 'active' : '' }}">Sudah Dibaca</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom p-0 overflow-hidden">
                @if(isset($messages) && count($messages) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($messages as $msg)
                        <a href="{{ route('inbox.show', $msg['id']) }}" class="list-group-item list-group-item-action p-3 p-md-3 border-bottom inbox-item {{ ($msg['read'] ?? false) ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
                                    <div class="position-relative flex-shrink-0">
                                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                            <i class="fas fa-user text-info"></i>
                                        </div>
                                        @if(!($msg['read'] ?? false))
                                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="mb-0 fw-bold {{ ($msg['read'] ?? false) ? 'text-muted' : 'text-dark' }} text-truncate">
                                                {{ $msg['from'] ?? 'Pengirim' }}
                                            </h6>
                                            @if(!($msg['read'] ?? false))
                                                <span class="badge bg-danger rounded-pill flex-shrink-0" style="font-size:9px;">Baru</span>
                                            @endif
                                        </div>
                                        <p class="mb-0 small text-muted text-truncate">
                                            @if(!empty($msg['product_name']))
                                                <span class="badge bg-primary bg-opacity-10 text-primary me-1">{{ $msg['product_name'] }}</span>
                                            @endif
                                            {{ $msg['preview'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                                <small class="text-muted flex-shrink-0 ms-2 d-none d-sm-block">
                                    {{ $msg['time'] ?? '' }}
                                </small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
                        <h5 class="text-muted">Belum ada pesan</h5>
                        <p class="text-muted small">Pesan dari pembeli akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            @php
                $total = count($messages ?? []);
                $unread = collect($messages ?? [])->where('read', false)->count();
                $read = $total - $unread;
            @endphp
            <div class="card-custom p-3 p-md-4">
                <h5 class="fw-bold mb-3 fs-6"><i class="fas fa-chart-bar text-primary me-2"></i> Ringkasan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Pesan</span>
                    <span class="fw-bold">{{ $total }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Belum Dibaca</span>
                    <span class="fw-bold text-danger">{{ $unread }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Sudah Dibaca</span>
                    <span class="fw-bold text-success">{{ $read }}</span>
                </div>
            </div>

            <div class="tips-card mt-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; padding: 1.5rem;">
                <h5 class="fw-bold mb-3 fs-6"><i class="fas fa-lightbulb text-warning me-2"></i> Tips</h5>
                <ul class="list-unstyled small opacity-90 mb-0">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Respons cepat meningkatkan kepercayaan</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Selalu sapa pembeli dengan ramah</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i>Berikan info lengkap tentang produk</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
