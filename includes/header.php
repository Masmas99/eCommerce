<?php
require_once __DIR__ . '/../config/config.php';
$cart_count = get_cart_count();
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | PIDII' : 'PIDII — High-Fashion E-Commerce' ?></title>
    
    <!-- PWA Manifest & iOS Support -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#0F0F0F">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="PIDII">
    <link rel="apple-touch-icon" href="<?= base_url('assets/placeholder.php?text=PIDII&w=192&h=192') ?>">

    <!-- Service Worker Registration & PWA Installer Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                    .then(reg => console.log('ServiceWorker registered:', reg.scope))
                    .catch(err => console.log('ServiceWorker error:', err));
            });
        }
        
        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        function triggerPWAInstall() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choice) => {
                    if (choice.outcome === 'accepted') {
                        console.log('PIDII App installed');
                    }
                    deferredPrompt = null;
                });
            } else {
                alert('✦ CARA INSTALL APLIKASI PIDII:\n\n' +
                      '📱 Di Android / Chrome:\n' +
                      'Ketuk ikon titik tiga di kanan atas > pilih "Install aplikasi" atau "Tambahkan ke Utama".\n\n' +
                      '🍎 Di iPhone / Safari:\n' +
                      'Ketuk tombol "Share" (bagian bawah) > pilih "Add to Home Screen" (Tambahkan ke Layar Utama).');
            }
        }
    </script>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            black: '#0F0F0F',
                            offwhite: '#F9F9F9',
                            gray: '#6B7280',
                            border: '#E5E7EB'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F9F9F9;
            color: #0F0F0F;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            animation: pageFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes pageFadeIn {
            0% {
                opacity: 0;
                transform: translateY(8px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .serif-title {
            font-family: 'Playfair Display', serif;
        }
        /* Custom scrollbar for minimalist sleek aesthetic */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F9F9F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #0F0F0F;
        }

        /* Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        /* Magnetic button hover sweep */
        .btn-sweep {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-sweep::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #27272a;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: -1;
        }
        .btn-sweep:hover::before {
            transform: translateX(100%);
        }

        /* Image Slow Zoom Pan */
        .img-zoom-container {
            overflow: hidden;
        }
        .img-zoom-container img {
            transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1), filter 0.5s ease;
        }
        .img-zoom-container:hover img {
            transform: scale(1.08);
            filter: brightness(1.05);
        }

        /* Card Floating Lift */
        .card-lift {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
        }
        .card-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        /* Hero Zoom Animation */
        @keyframes heroPulseZoom {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .hero-zoom-bg {
            animation: heroPulseZoom 20s ease-in-out infinite alternate;
        }
    </style>

    <!-- Scroll Observer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => observer.observe(el));
        });
    </script>
</head>
<body class="min-h-screen flex flex-col justify-between bg-[#F9F9F9] text-brand-black" x-data="{ mobileMenuOpen: false, searchOpen: false, currency: 'IDR' }">

    <!-- Top Announcement Bar -->
    <?php include __DIR__ . '/top_bar.php'; ?>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left Nav Links -->
                <nav class="hidden md:flex items-center space-x-8 text-xs font-bold uppercase tracking-widest">
                    <a href="<?= base_url('products.php') ?>" class="hover:text-gray-500 transition-colors py-2 border-b-2 border-transparent hover:border-black">Koleksi</a>
                    <a href="<?= base_url('products.php?category=outerwear-tailored') ?>" class="hover:text-gray-500 transition-colors py-2">Outerwear</a>
                    <a href="<?= base_url('products.php?category=minimalist-tops') ?>" class="hover:text-gray-500 transition-colors py-2">Tops</a>
                    <a href="<?= base_url('products.php?category=footwear-boots') ?>" class="hover:text-gray-500 transition-colors py-2">Footwear</a>
                    <a href="<?= base_url('products.php?badge=Limited+Edition') ?>" class="text-rose-600 font-extrabold hover:opacity-80 transition-opacity">Limited</a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 text-black hover:text-gray-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Brand Logo (Center / Left on mobile) -->
                <div class="flex-1 md:flex-none text-center">
                    <a href="<?= base_url() ?>" class="inline-block">
                        <span class="text-2xl md:text-3xl font-black tracking-[0.25em] uppercase border-y-2 border-black py-1 px-3">PIDII</span>
                    </a>
                </div>

                <!-- Right Utility Icons & Actions -->
                <div class="flex items-center space-x-3 sm:space-x-5 text-xs uppercase font-bold tracking-wider">
                    
                    <!-- PWA Install Button -->
                    <button onclick="triggerPWAInstall()" class="flex items-center space-x-1 border border-black px-2.5 py-1 text-[11px] font-black hover:bg-black hover:text-white transition-colors" title="Install App PIDII">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span class="hidden sm:inline">INSTALL APP</span>
                    </button>
                    
                    <!-- Currency Selector -->
                    <div class="relative hidden sm:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1 py-1 px-2 border border-gray-300 rounded-none hover:border-black text-[11px]">
                            <span x-text="currency">IDR</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-24 bg-white border border-black shadow-none py-1 z-50 rounded-none">
                            <button @click="currency = 'IDR'; open = false" class="block w-full text-left px-4 py-2 hover:bg-black hover:text-white transition-colors">IDR (Rp)</button>
                            <button @click="currency = 'USD'; open = false" class="block w-full text-left px-4 py-2 hover:bg-black hover:text-white transition-colors">USD ($)</button>
                            <button @click="currency = 'EUR'; open = false" class="block w-full text-left px-4 py-2 hover:bg-black hover:text-white transition-colors">EUR (€)</button>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button @click="searchOpen = true" class="p-1 hover:text-gray-500 transition-colors focus:outline-none" title="Search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

                    <!-- Cart Link -->
                    <a href="<?= base_url('cart.php') ?>" class="relative p-1 hover:text-gray-500 transition-colors flex items-center" title="Bag / Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <?php if ($cart_count > 0): ?>
                            <span class="absolute -top-1 -right-2 bg-black text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"><?= $cart_count ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- User Account / Profile Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <?php if (is_logged_in()): ?>
                            <button @click="userOpen = !userOpen" class="flex items-center space-x-1 p-1 hover:text-gray-500 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </button>
                            <div x-show="userOpen" @click.away="userOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-black shadow-none py-2 z-50 rounded-none text-left">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="font-bold text-xs truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
                                    <p class="text-[10px] text-gray-500 lowercase truncate"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
                                </div>
                                <?php if (is_admin()): ?>
                                    <a href="<?= base_url('admin/index.php') ?>" class="block px-4 py-2 text-xs font-bold text-rose-600 hover:bg-gray-100">Dashboard Admin</a>
                                <?php endif; ?>
                                <a href="<?= base_url('my-orders.php') ?>" class="block px-4 py-2 text-xs hover:bg-black hover:text-white">Pesanan Saya</a>
                                <a href="<?= base_url('logout.php') ?>" class="block px-4 py-2 text-xs text-red-600 hover:bg-red-50">Logout</a>
                            </div>
                        <?php else: ?>
                            <a href="<?= base_url('login.php') ?>" class="hidden sm:inline-block border border-black px-3 py-1.5 hover:bg-black hover:text-white transition-colors text-[11px] font-bold">
                                LOGIN
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 pt-3 pb-6 space-y-3 uppercase font-bold text-xs">
                <a href="<?= base_url('products.php') ?>" class="block py-2 border-b border-gray-100">Semua Koleksi</a>
                <a href="<?= base_url('products.php?category=outerwear-tailored') ?>" class="block py-2 border-b border-gray-100">Outerwear & Tailored</a>
                <a href="<?= base_url('products.php?category=minimalist-tops') ?>" class="block py-2 border-b border-gray-100">Minimalist Tops</a>
                <a href="<?= base_url('products.php?category=trousers-skirts') ?>" class="block py-2 border-b border-gray-100">Trousers & Skirts</a>
                <a href="<?= base_url('products.php?category=footwear-boots') ?>" class="block py-2 border-b border-gray-100">Footwear & Boots</a>
                <a href="<?= base_url('products.php?category=leather-accessories') ?>" class="block py-2 border-b border-gray-100">Leather Accessories</a>
                <?php if (!is_logged_in()): ?>
                    <a href="<?= base_url('login.php') ?>" class="block w-full text-center bg-black text-white py-3 mt-4">Masuk / Daftar</a>
                <?php else: ?>
                    <a href="<?= base_url('my-orders.php') ?>" class="block py-2">Pesanan Saya</a>
                    <a href="<?= base_url('logout.php') ?>" class="block py-2 text-red-600">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Global Search Modal Overlay -->
    <div x-show="searchOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-start justify-center pt-20 px-4">
        <div @click.away="searchOpen = false" class="bg-white w-full max-w-2xl p-6 rounded-none shadow-2xl relative">
            <button @click="searchOpen = false" class="absolute top-4 right-4 text-black hover:text-gray-500 font-bold text-xl">&times;</button>
            <h3 class="text-sm font-bold uppercase tracking-widest mb-4">Cari Produk Minimalist</h3>
            <form action="<?= base_url('products.php') ?>" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Ketik kata kunci (contoh: Coat, Silk, Leather)..." autofocus class="w-full border-2 border-black p-3 text-sm focus:outline-none font-medium">
                <button type="submit" class="bg-black text-white px-6 py-3 font-bold text-xs uppercase tracking-wider hover:bg-neutral-800">Cari</button>
            </form>
            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                <span class="text-gray-500">Pencarian Populer:</span>
                <a href="<?= base_url('products.php?search=Wool') ?>" class="underline hover:text-black">Wool Coat</a>
                <a href="<?= base_url('products.php?search=Silk') ?>" class="underline hover:text-black">Raw Silk</a>
                <a href="<?= base_url('products.php?search=Leather') ?>" class="underline hover:text-black">Leather Tote</a>
                <a href="<?= base_url('products.php?search=Blazer') ?>" class="underline hover:text-black">Blazer</a>
            </div>
        </div>
    </div>

    <!-- Flash Messages Alert -->
    <?php if ($flash): ?>
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-none font-medium text-sm flex items-center justify-between <?= $flash['type'] === 'success' ? 'bg-black text-white' : ($flash['type'] === 'error' ? 'bg-rose-900 text-white' : 'bg-gray-800 text-white') ?>">
                <span><?= htmlspecialchars($flash['message']) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-4 font-bold text-lg">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content Container Wrapper -->
    <main class="flex-grow">
