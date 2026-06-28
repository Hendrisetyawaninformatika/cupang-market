@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<style>
    /* ============================================
       ANIMATED BACKGROUND & BASE
       ============================================ */
    .home-wrapper {
        position: relative;
        overflow: hidden;
    }

    .home-wrapper::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(ellipse at 20% 50%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 20%, rgba(118, 75, 162, 0.06) 0%, transparent 50%),
            radial-gradient(ellipse at 40% 80%, rgba(56, 189, 248, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    /* Floating bubbles */
    .bubble {
        position: fixed;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.1));
        pointer-events: none;
        z-index: 0;
        animation: bubble-float linear infinite;
    }

    .bubble-1 { width: 60px; height: 60px; top: 10%; left: 5%; animation-duration: 20s; animation-delay: 0s; }
    .bubble-2 { width: 40px; height: 40px; top: 60%; left: 85%; animation-duration: 25s; animation-delay: 2s; }
    .bubble-3 { width: 80px; height: 80px; top: 80%; left: 20%; animation-duration: 18s; animation-delay: 4s; }
    .bubble-4 { width: 30px; height: 30px; top: 30%; left: 90%; animation-duration: 22s; animation-delay: 1s; }
    .bubble-5 { width: 50px; height: 50px; top: 50%; left: 50%; animation-duration: 28s; animation-delay: 3s; }

    @keyframes bubble-float {
        0% { transform: translateY(0) rotate(0deg) scale(1); opacity: 0.3; }
        25% { transform: translateY(-30px) rotate(90deg) scale(1.1); opacity: 0.5; }
        50% { transform: translateY(-60px) rotate(180deg) scale(0.9); opacity: 0.4; }
        75% { transform: translateY(-30px) rotate(270deg) scale(1.05); opacity: 0.5; }
        100% { transform: translateY(0) rotate(360deg) scale(1); opacity: 0.3; }
    }

    /* ============================================
       HERO SECTION - ANIMATED
       ============================================ */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #0ea5e9 100%);
        border-radius: 24px;
        padding: 4rem 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        color: white;
        box-shadow: 0 25px 50px -12px rgba(102, 126, 234, 0.4);
        animation: hero-glow 4s ease-in-out infinite alternate;
    }

    @keyframes hero-glow {
        0% { box-shadow: 0 25px 50px -12px rgba(102, 126, 234, 0.4); }
        100% { box-shadow: 0 30px 60px -12px rgba(102, 126, 234, 0.6), 0 0 40px rgba(102, 126, 234, 0.2); }
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: hero-orb 8s ease-in-out infinite;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
        animation: hero-orb 10s ease-in-out infinite reverse;
    }

    @keyframes hero-orb {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, -30px) scale(1.1); }
    }

    .hero-title {
        font-size: 2.75rem;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s ease;
    }

    .hero-title span {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .hero-title span::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
        border-radius: 2px;
        animation: underline-grow 1s ease 0.5s both;
    }

    @keyframes underline-grow {
        from { width: 0; }
        to { width: 100%; }
    }

    .hero-text {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        max-width: 500px;
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s ease 0.2s both;
    }

    .hero-btn {
        position: relative;
        z-index: 1;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        animation: fadeInUp 0.8s ease 0.4s both;
    }

    .hero-btn-light {
        background: white;
        color: #667eea;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .hero-btn-light:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }

    .hero-btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .hero-btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    /* Fish swimming animation */
    .fish-swim {
        position: absolute;
        font-size: 2rem;
        opacity: 0.15;
        z-index: 1;
        animation: swim 15s linear infinite;
    }

    .fish-1 { top: 20%; right: 10%; animation-delay: 0s; }
    .fish-2 { top: 60%; right: 25%; animation-delay: 5s; font-size: 1.5rem; }
    .fish-3 { top: 40%; right: 5%; animation-delay: 10s; font-size: 1.2rem; }

    @keyframes swim {
        0% { transform: translateX(0) scaleX(1); }
        49% { transform: translateX(-200px) scaleX(1); }
        50% { transform: translateX(-200px) scaleX(-1); }
        99% { transform: translateX(0) scaleX(-1); }
        100% { transform: translateX(0) scaleX(1); }
    }

    /* ============================================
       SEARCH BAR - ANIMATED
       ============================================ */
    .search-bar {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        animation: fadeInUp 0.8s ease 0.6s both;
        position: relative;
        z-index: 1;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        transition: all 0.3s ease;
    }

    .form-control-custom {
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
    }

    .form-control-custom:focus {
        background: white;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .search-input-wrapper .form-control-custom {
        padding-left: 2.75rem;
    }

    .search-input-wrapper:focus-within i {
        color: #764ba2;
        transform: translateY(-50%) scale(1.1);
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* ============================================
       SECTION TITLE - ANIMATED
       ============================================ */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .section-title i {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .section-title i.text-primary {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .section-title i.text-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* ============================================
       CATEGORY CARDS - ANIMATED
       ============================================ */
    .category-row {
        position: relative;
        z-index: 1;
    }

    .category-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1.25rem 0.75rem;
        border-radius: 20px;
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        text-decoration: none;
        color: inherit;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        min-height: 130px;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        animation: fadeInUp 0.6s ease both;
    }

    .category-card:nth-child(1) { animation-delay: 0.1s; }
    .category-card:nth-child(2) { animation-delay: 0.2s; }
    .category-card:nth-child(3) { animation-delay: 0.3s; }
    .category-card:nth-child(4) { animation-delay: 0.4s; }
    .category-card:nth-child(5) { animation-delay: 0.5s; }
    .category-card:nth-child(6) { animation-delay: 0.6s; }

    .category-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--cat-color, #667eea) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        border-radius: 20px;
    }

    .category-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        border-color: transparent;
    }

    .category-card:hover::before {
        opacity: 0.05;
    }

    .category-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: white;
        margin-bottom: 0.75rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .category-card:hover .category-icon {
        transform: scale(1.15) rotate(-5deg);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .category-name {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .category-desc {
        font-size: 0.7rem;
        color: #6c757d;
        position: relative;
        z-index: 1;
    }

    /* ============================================
       FILTER PILLS - ANIMATED
       ============================================ */
    .filter-pills {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        position: relative;
        z-index: 1;
    }

    .filter-pills::-webkit-scrollbar { 
        display: none;
    }

    .filter-pill {
        white-space: nowrap;
        padding: 0.625rem 1.25rem;
        border-radius: 50px;
        background: white;
        border: 1px solid #e9ecef;
        color: #495057;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .filter-pill::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50px;
    }

    .filter-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        border-color: #667eea;
    }

    .filter-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        transform: scale(1.05);
    }

    .filter-pill span {
        position: relative;
        z-index: 1;
    }

    /* ============================================
       PRODUCT CARDS - ANIMATED
       ============================================ */
    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.6s ease both;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .product-img-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .product-card:hover .product-img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
        animation: badge-pulse 2s ease-in-out infinite;
    }

    .badge-mine {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    @keyframes badge-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .product-body {
        padding: 1.25rem;
    }

    .product-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a2e;
        line-height: 1.3;
    }

    .product-desc {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 800;
        color: #667eea;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .product-seller {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .product-seller i {
        color: #667eea;
    }

    /* ============================================
       PRODUCT CARD - MOBILE HORIZONTAL LAYOUT
       ============================================ */
    @media (max-width: 576px) {
        .product-card {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            border-radius: 16px;
        }

        .product-img-wrapper {
            width: 120px;
            min-width: 120px;
            height: auto;
            border-radius: 16px 0 0 16px;
        }

        .product-img {
            border-radius: 16px 0 0 16px;
        }

        .product-body {
            flex: 1;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-category {
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
            margin-bottom: 0.35rem;
        }

        .product-name {
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
        }

        .product-desc {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
            -webkit-line-clamp: 1;
        }

        .product-price {
            font-size: 1rem;
        }

        .product-seller {
            font-size: 0.7rem;
            margin-top: 0.35rem;
        }

        .product-badge {
            top: 8px;
            right: auto;
            left: 8px;
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
    }

    /* ============================================
       EMPTY STATE - ANIMATED
       ============================================ */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 24px;
        border: 2px dashed #e9ecef;
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s ease;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
        display: inline-block;
        animation: empty-bounce 2s ease-in-out infinite;
    }

    @keyframes empty-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    /* ============================================
       WHY CHOOSE US - ANIMATED
       ============================================ */
    .card-custom {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .card-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #0ea5e9);
        animation: rainbow-line 3s linear infinite;
        background-size: 200% 100%;
    }

    @keyframes rainbow-line {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1.25rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: icon-float 3s ease-in-out infinite;
    }

    .stat-icon:hover {
        transform: scale(1.1) rotate(5deg);
    }

    @keyframes icon-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* ============================================
       FOOTER - CUPANG JOGJA
       ============================================ */
    .footer-cupang {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #94a3b8;
        padding: 4rem 0 2rem;
        margin-top: 4rem;
        position: relative;
        overflow: hidden;
    }

    .footer-cupang::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #0ea5e9, #10b981, #f59e0b);
        background-size: 300% 100%;
        animation: footer-rainbow 4s linear infinite;
    }

    @keyframes footer-rainbow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .footer-cupang::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: radial-gradient(ellipse at 50% 100%, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .footer-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .footer-brand-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        animation: footer-icon-glow 3s ease-in-out infinite;
    }

    @keyframes footer-icon-glow {
        0%, 100% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); }
        50% { box-shadow: 0 4px 25px rgba(102, 126, 234, 0.5); }
    }

    .footer-brand-text h4 {
        color: #f8fafc;
        font-weight: 700;
        margin: 0;
        font-size: 1.25rem;
    }

    .footer-brand-text span {
        color: #38bdf8;
        font-weight: 600;
    }

    .footer-desc {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .footer-title {
        color: #f8fafc;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1.25rem;
        position: relative;
        display: inline-block;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 30px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.75rem;
    }

    .footer-links a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .footer-links a:hover {
        color: #38bdf8;
        transform: translateX(5px);
    }

    .footer-links a i {
        font-size: 0.7rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .footer-links a:hover i {
        opacity: 1;
    }

    .footer-contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.875rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .footer-contact-item i {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .footer-contact-item:hover i {
        background: #667eea;
        color: white;
        transform: scale(1.1);
    }

    .footer-social {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;

    .contact-phones ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .contact-phones ul li {
        margin-bottom: 0.25rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }
    .contact-phones ul li:last-child {
        margin-bottom: 0;
    }
    }

    .footer-social a {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-size: 1rem;
    }

    .footer-social a:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: transparent;
        transform: translateY(-4px) scale(1.1);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.05);
        margin-top: 3rem;
        padding-top: 2rem;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .footer-bottom p {
        color: #64748b;
        font-size: 0.8125rem;
        margin: 0;
    }

    .footer-bottom .heart {
        color: #ef4444;
        display: inline-block;
        animation: heartbeat 1.5s ease-in-out infinite;
    }

    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        14% { transform: scale(1.3); }
        28% { transform: scale(1); }
        42% { transform: scale(1.3); }
        70% { transform: scale(1); }
    }

    .footer-fish {
        position: absolute;
        font-size: 1.2rem;
        opacity: 0.1;
        color: #38bdf8;
        animation: footer-swim 20s linear infinite;
    }

    .footer-fish-1 { top: 20%; left: -50px; animation-delay: 0s; }
    .footer-fish-2 { top: 50%; left: -50px; animation-delay: 7s; font-size: 0.9rem; }
    .footer-fish-3 { top: 80%; left: -50px; animation-delay: 14s; font-size: 1.5rem; }

    @keyframes footer-swim {
        0% { left: -50px; }
        100% { left: 110%; }
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Scroll reveal */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 768px) {
        .hero-section {
            padding: 2.5rem 1.5rem;
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

        .category-card {
            padding: 1rem 0.5rem;
            min-height: 110px;
        }

        .category-icon {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }

        .footer-cupang {
            padding: 2.5rem 0 1.5rem;
        }
    }

    /* Utility colors */
    .bg-purple {
        background-color: #8b5cf6 !important;
    }

    .text-purple {
        color: #8b5cf6 !important;
    }
</style>
<meta name="viewport" content="width=1024">

<div class="home-wrapper">
    <!-- Floating bubbles background -->
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <div class="bubble bubble-3"></div>
    <div class="bubble bubble-4"></div>
    <div class="bubble bubble-5"></div>

    <div class="container py-4" style="position: relative; z-index: 1;">
        <!-- Hero -->
        <div class="hero-section reveal">
            <i class="fas fa-fish fish-swim fish-1"></i>
            <i class="fas fa-fish fish-swim fish-2"></i>
            <i class="fas fa-fish fish-swim fish-3"></i>

            <h1 class="hero-title">Temukan Ikan Cupang <span>Berkualitas</span></h1>
            <p class="hero-text">Ribuan koleksi ikan cupang dari breeder terpercaya. Bergaransi hidup sampai tujuan!</p>
            <div class="d-flex gap-3">
                @if(session('firebase_user'))
                    <a href="{{ route('products.create') }}" class="hero-btn hero-btn-success">
                        <i class="fas fa-plus"></i> Jual Cupang
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hero-btn hero-btn-success">
                        Masuk Akun 
                    </a>
                @endif
            </div>
        </div>

        <!-- Search -->
        <div class="search-bar reveal">
            <form action="{{ route('home') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" class="form-control form-control-custom ps-5" placeholder="Cari ikan cupang..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                      
                    </div>
                </div>
            </form>
        </div>

        <!-- Categories -->
        <h3 class="section-title reveal"><i class="fas fa-th-large text-primary"></i> Kategori</h3>
        <div class="row g-3 category-row reveal">
            <div class="col-4 col-md-2">
                <a href="{{ route('home', ['category' => 'Halfmoon']) }}" class="category-card" style="--cat-color: #667eea;">
                    <div class="category-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="category-name">Halfmoon</div>
                    <div class="category-desc">Sirip lebar</div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('home', ['category' => 'Plakat']) }}" class="category-card" style="--cat-color: #ef4444;">
                    <div class="category-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="category-name">Plakat</div>
                    <div class="category-desc">Sirip pendek</div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('home', ['category' => 'Crowntail']) }}" class="category-card" style="--cat-color: #f59e0b;">
                    <div class="category-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="category-name">Crowntail</div>
                    <div class="category-desc">Sirip berduri</div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('home', ['category' => 'Double Tail']) }}" class="category-card" style="--cat-color: #10b981;">
                    <div class="category-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="category-name">Double Tail</div>
                    <div class="category-desc">Dua ekor</div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('home', ['category' => 'Super Delta']) }}" class="category-card" style="--cat-color: #0ea5e9;">
                    <div class="category-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="category-name">Super Delta</div>
                    <div class="category-desc">Sirip Delta</div>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('home', ['category' => 'Lainnya']) }}" class="category-card" style="--cat-color: #8b5cf6;">
                    <div class="category-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-fish"></i>
                    </div>
                    <div class="category-name">Lainnya</div>
                    <div class="category-desc">Jenis lain</div>
                </a>
            </div>
        </div>
</div>
        

       

        @if(count($products) > 0)
            <div class="row g-3">
                @foreach($products as $id => $product)
                <div class="col-12 col-lg-3 reveal">
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
                            <div class="product-seller"><i class="fas fa-user-circle"></i> {{ $product['sellerName'] ?? 'Anonim' }}</div>
                            @if(session('firebase_user') && ($product['sellerId'] ?? '') === session('firebase_user.uid'))
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('products.edit', $id) }}" class="btn btn-sm btn-warning text-white flex-fill">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            @else
                                <button type="button" class="btn btn-sm btn-success w-100 mt-2" data-bs-toggle="modal" data-bs-target="#messageModal"
                                    data-receiver-id="{{ $product['sellerId'] ?? '' }}"
                                    data-receiver-phone="{{ preg_replace('/[^0-9]/', '', $product['sellerPhone'] ?? $product['sellerWhatsapp'] ?? '') }}"
                                    data-product-id="{{ $id }}"
                                    data-product-name="{{ $product['name'] ?? 'Produk' }}"
                                    data-product-price="Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}"
                                    data-product-image="{{ $product['imageBase64'] ?? '' }}">
                                    <i class="fab fa-whatsapp me-1"></i> Chat Penjual
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state reveal">
                <i class="fas fa-fish"></i>
                <h4 class="fw-bold text-muted">Belum ada ikan</h4>
                <p class="text-muted">Jadilah yang pertama menjual ikan cupang!</p>
                @if(session('firebase_user'))
                    <a href="{{ route('products.create') }}" class="btn btn-gradient mt-3">
                        <i class="fas fa-plus me-2"></i> Tambah Ikan
                    </a>
                @endif
            </div>
        @endif

        <!-- Why Choose Us -->
        <div class="card-custom p-5 mt-5 reveal">
            <h3 class="text-center fw-bold mb-5">Mengapa Cupang Market?</h3>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    
                    <h5 class="fw-bold">Garansi Hidup</h5>
                    <p class="text-muted small">Jaminan ikan hidup sampai tujuan</p>
                </div>
                <div class="col-md-4 text-center">
                   
                    <h5 class="fw-bold">Pengiriman Aman</h5>
                    <p class="text-muted small">Pengemasan khusus dengan oksigen</p>
                </div>
                <div class="col-md-4 text-center">
                    
                    <h5 class="fw-bold">Breeder Terpercaya</h5>
                    <p class="text-muted small">Penjual terverifikasi</p>
                </div>
            </div>
        </div>
    
</div>

<!-- Modal Kirim Pesan -->

   <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:20px;border:none;box-shadow:0 25px 50px rgba(0,0,0,0.3);">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="messageModalLabel"><i class="fab fa-whatsapp text-success me-2"></i>Kirim Pesan ke Penjual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body pt-2">
        <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
          <img id="modalProductImg" src="" class="rounded-3" style="width:60px;height:60px;object-fit:cover;display:none;">
          <div id="modalProductImgPlaceholder" class="rounded-3 bg-white d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
            <i class="fas fa-fish text-muted"></i>
          </div>
          <div>
            <p class="fw-bold mb-0" id="modalProductName">Nama Produk</p>
            <p class="text-muted small mb-0" id="modalProductPrice">Rp 0</p>
          </div>
        </div>
        <form id="messageForm" action="{{ route('messages.store') }}" method="POST">
          @csrf
          <input type="hidden" name="receiver_id" id="modalReceiverId">
          <input type="hidden" name="product_id" id="modalProductId">
          <input type="hidden" name="product_name" id="modalProductNameInput">

          <div class="mb-3">
            <label class="form-label fw-bold small">Nama Kamu</label>
            <input type="text" name="sender_name" class="form-control" value="{{ session('firebase_user.displayName') ?? 'Pembeli' }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Nomor WhatsApp</label>
            <input type="text" name="sender_phone" class="form-control" placeholder="08xxxxxxxxxx" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Pesan</label>
            <textarea name="content" class="form-control" rows="3" placeholder="Halo, apakah produk ini masih ready?" required></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-fill"><i class="fab fa-whatsapp me-2"></i>Kirim Pesan</button>
            <a id="modalWaLink" href="#" target="_blank" class="btn btn-outline-success"><i class="fab fa-whatsapp"></i> WA Langsung</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer Cupang Jogja -->
<footer class="footer-cupang">
    <i class="fas fa-fish footer-fish footer-fish-1"></i>
    <i class="fas fa-fish footer-fish footer-fish-2"></i>
    <i class="fas fa-fish footer-fish footer-fish-3"></i>

    <div class="container">
    <div class="row g-4">
        <!-- Kolom 1: Brand & Deskripsi -->
        <div class="col-lg-4 col-md-6">
            <div class="footer-brand">
                <!-- ✅ GANTI JADI GAMBAR URL -->
                <div class="footer-brand-icon">
                    <img src="https://cdn.corenexis.com/f/DmqbTrJmPkp.png" 
                         alt="Cupang Market" 
                         height="45" 
                         style="border-radius: 10px; object-fit: contain;">
                </div>
                <div class="footer-brand-text">
                    <h4>Cupang <span>Market</span></h4>
                </div>
            </div>
            <p class="footer-desc">
                Marketplace ikan cupang terpercaya di Yogyakarta. Menyediakan berbagai jenis ikan cupang berkualitas dari breeder terbaik.
            </p>
            <div class="footer-social">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
     
            <!-- Kolom 2: Menu Navigasi -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title">Menu</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                    <li><a href="#products"><i class="fas fa-chevron-right"></i> Produk</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Kategori</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Tentang Kami</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Kategori Populer -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Kategori Populer</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home', ['category' => 'Halfmoon']) }}"><i class="fas fa-chevron-right"></i> Halfmoon</a></li>
                    <li><a href="{{ route('home', ['category' => 'Plakat']) }}"><i class="fas fa-chevron-right"></i> Plakat</a></li>
                    <li><a href="{{ route('home', ['category' => 'Crowntail']) }}"><i class="fas fa-chevron-right"></i> Crowntail</a></li>
                    <li><a href="{{ route('home', ['category' => 'Double Tail']) }}"><i class="fas fa-chevron-right"></i> Double Tail</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Kontak (Sudah Update Google Maps, Gmail, & List Nomor) -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Kontak Kami</h5>
                
                <!-- Link Google Maps -->
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <a href="https://maps.app.goo.gl/uDhSHjN5Kcj8cAYt8" target="_blank" rel="noopener noreferrer">
                        <span>Jl. Ring Road Utara, Daerah Istimewa Yogyakarta 55281</span>
                    </a>
                </div>

                <!-- List Nomor Telepon Urut Bawah -->
                <div class="footer-contact-item contact-phones">
                    <i class="fas fa-phone"></i>
                    <ul>
                        <li>+62 81248141031</li>
                        <li>+62 85802090008</li>
                        <li>+62 83844137001</li>
                        <li>+62 85641125230</li>
                    </ul>
                </div>

                <!-- Link Google Mail (Gmail) -->
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=cupangjogja@gmail.com" target="_blank" rel="noopener noreferrer">
                        <span>cupangjogja@gmail.com</span>
                    </a>
                </div>

                <div class="footer-contact-item">
                    <i class="fas fa-clock"></i>
                    <span>Senin - Sabtu: 08.00 - 20.00</span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Cupang Jogja. NAAH Wibu di Yogyakarta</p>
        </div>
    </div>
</footer>


@endsection

@section('scripts')
<script>
    // Scroll reveal animation
    document.addEventListener('DOMContentLoaded', function() {
        const reveals = document.querySelectorAll('.reveal');

        function checkReveal() {
            const windowHeight = window.innerHeight;
            const elementVisible = 100;

            reveals.forEach((reveal) => {
                const elementTop = reveal.getBoundingClientRect().top;
                if (elementTop < windowHeight - elementVisible) {
                    reveal.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', checkReveal);
        checkReveal(); // Check on load
    });


    // Message Modal Handler
    const messageModal = document.getElementById('messageModal');
    if (messageModal) {
        messageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Get data from button
            const receiverId = button.getAttribute('data-receiver-id');
            const receiverPhone = button.getAttribute('data-receiver-phone');
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productPrice = button.getAttribute('data-product-price');
            const productImage = button.getAttribute('data-product-image');

            // Fill modal
            document.getElementById('modalReceiverId').value = receiverId;
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductNameInput').value = productName;
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductPrice').textContent = productPrice;

            // Product image
            const imgEl = document.getElementById('modalProductImg');
            const placeholderEl = document.getElementById('modalProductImgPlaceholder');
            if (productImage) {
                imgEl.src = productImage;
                imgEl.style.display = 'block';
                placeholderEl.style.display = 'none';
            } else {
                imgEl.style.display = 'none';
                placeholderEl.style.display = 'flex';
            }

            // WA direct link
            const waLink = document.getElementById('modalWaLink');
            if (receiverPhone) {
                waLink.href = 'https://wa.me/' + receiverPhone + '?text=Halo,%20saya%20tertarik%20dengan%20' + encodeURIComponent(productName);
                waLink.style.display = 'inline-block';
            } else {
                waLink.style.display = 'none';
            }
        });
    }
</script>
@endsection
