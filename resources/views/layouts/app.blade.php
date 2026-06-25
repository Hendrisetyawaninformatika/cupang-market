<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cupang Market')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --success: #10b981; --danger: #ef4444; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; min-height: 100vh; }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 12px 0;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }
        .navbar-brand { font-weight: 700; font-size: 24px; color: white !important; }
        .navbar-brand i { margin-right: 10px; }
        .nav-link-custom {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 10px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%; background: white;
            color: var(--primary); display: flex; align-items: center;
            justify-content: center; font-weight: 700; font-size: 14px;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; border: none; border-radius: 12px;
            padding: 10px 24px; font-weight: 600; transition: all 0.3s;
        }
        .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); color: white; }
        .btn-gradient:disabled { opacity: 0.7; cursor: not-allowed; }
        
        .btn-success-custom {
            background: var(--success); color: white; border: none;
            border-radius: 12px; padding: 10px 24px; font-weight: 600;
        }
        .btn-success-custom:hover { background: #059669; color: white; }
        
        .card-custom {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s;
        }
        .card-custom:hover { box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        
        .product-card {
            background: white; border-radius: 20px; overflow: hidden;
            border: 1px solid #e2e8f0; transition: all 0.3s; height: 100%;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 50px rgba(0,0,0,0.1); }
        .product-img-wrapper { position: relative; height: 200px; overflow: hidden; background: #f1f5f9; }
        .product-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .product-card:hover .product-img { transform: scale(1.05); }
        .product-badge { position: absolute; top: 10px; left: 10px; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .badge-mine { background: var(--danger); color: white; }
        .product-body { padding: 20px; }
        .product-category { display: inline-block; background: #eff6ff; color: var(--primary); padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-bottom: 8px; }
        .product-name { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-desc { font-size: 13px; color: #64748b; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-price { font-size: 18px; font-weight: 700; color: var(--primary); }
        .product-seller { font-size: 12px; color: #94a3b8; margin-top: 8px; }
        
        .category-card {
            background: white; border-radius: 16px; padding: 20px; text-align: center;
            border: 1px solid #e2e8f0; transition: all 0.3s; cursor: pointer;
            text-decoration: none; color: inherit; display: block;
        }
        .category-card:hover { border-color: var(--primary); box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15); transform: translateY(-3px); }
        .category-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px; color: white; }
        
        .stat-card { padding: 25px; }
        .stat-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 15px; }
        .stat-value { font-size: 32px; font-weight: 700; color: #1e293b; }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 20px; padding: 50px 40px; margin-bottom: 30px;
            position: relative; overflow: hidden; color: white;
        }
        .hero-title { font-size: 2.5rem; font-weight: 700; margin-bottom: 15px; }
        .hero-title span { color: #fde047; }
        
        .search-bar { background: white; border-radius: 16px; padding: 16px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        
        .form-control-custom { padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 14px; font-family: 'Poppins', sans-serif; }
        .form-control-custom:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1); }
        
        .image-upload-area { border: 2px dashed #cbd5e1; border-radius: 16px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8fafc; }
        .image-upload-area:hover { border-color: var(--primary); background: #eff6ff; }
        .upload-preview { max-width: 100%; max-height: 250px; border-radius: 12px; }
        
        .tips-card { background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 20px; padding: 24px; color: white; }
        
        .quick-action { display: flex; align-items: center; gap: 15px; padding: 16px; border-radius: 16px; transition: all 0.3s; text-decoration: none; color: inherit; }
        .quick-action:hover { transform: translateX(5px); }
        .quick-action-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; }
        
        .toast-container-custom { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .toast-custom { padding: 14px 24px; border-radius: 14px; color: white; font-weight: 500; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin-bottom: 10px; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 80px; color: #cbd5e1; margin-bottom: 20px; }
        
        .section-title { font-size: 20px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        
        .filter-pills { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }
        .filter-pill { padding: 8px 20px; border-radius: 20px; background: white; border: 2px solid #e2e8f0; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s; text-decoration: none; color: inherit; }
        .filter-pill:hover { border-color: var(--primary); color: var(--primary); }
        .filter-pill.active { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-color: transparent; }
        
        @media (max-width: 768px) { .hero-title { font-size: 1.8rem; } .hero-section { padding: 30px 20px; } }
    </style>
    @yield('styles')
</head>
<body>

    @if(session('firebase_user'))
    <nav class="navbar-custom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="{{ route('home') }}"><i class="fas fa-fish"></i> Cupang Market</a>
                <div class="d-flex align-items-center gap-3">
                    <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="fas fa-home me-1"></i> Beranda</a>
                    <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-chart-line me-1"></i> Dashboard</a>
                    <div class="dropdown">
                        <a class="nav-link-custom d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <div class="user-avatar">{{ strtoupper(substr(session('firebase_user.displayName', 'U'), 0, 1)) }}</div>
                            <span>{{ session('firebase_user.displayName', 'User') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 16px;">
                            <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="fas fa-chart-line me-2 text-primary"></i> Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent w-100 text-start"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endif

    <div class="toast-container-custom">
        @if(session('success'))
            <div class="toast-custom" style="background: linear-gradient(135deg, #10b981, #059669);"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="toast-custom" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="fas fa-times-circle me-2"></i> {{ session('error') }}</div>
        @endif
    </div>

    <main>@yield('content')</main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(() => { document.querySelectorAll('.toast-custom').forEach(t => t.remove()); }, 4000);
    </script>
    @yield('scripts')
</body>
</html>