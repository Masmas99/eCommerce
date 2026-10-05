<?php
// admin/includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-64 bg-black border-r border-neutral-800 flex flex-col justify-between flex-shrink-0">
    <div>
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 border-b border-neutral-800">
            <a href="<?= base_url('admin/index.php') ?>" class="inline-block">
                <span class="text-xl font-black tracking-[0.2em] uppercase border-y border-white py-0.5 px-2 text-white">PIDII</span>
                <span class="text-[9px] font-mono block text-neutral-400 mt-1 uppercase tracking-widest">CONTROL PANEL</span>
            </a>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="p-4 space-y-1 text-xs uppercase font-bold tracking-wider">
            <a href="<?= base_url('admin/index.php') ?>" class="flex items-center space-x-3 px-4 py-3 border border-transparent hover:border-neutral-700 <?= $current_page === 'index.php' ? 'bg-white text-black font-extrabold' : 'text-neutral-400 hover:text-white' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard Overview</span>
            </a>

            <a href="<?= base_url('admin/products.php') ?>" class="flex items-center space-x-3 px-4 py-3 border border-transparent hover:border-neutral-700 <?= $current_page === 'products.php' ? 'bg-white text-black font-extrabold' : 'text-neutral-400 hover:text-white' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Manajemen Produk</span>
            </a>

            <a href="<?= base_url('admin/orders.php') ?>" class="flex items-center space-x-3 px-4 py-3 border border-transparent hover:border-neutral-700 <?= $current_page === 'orders.php' ? 'bg-white text-black font-extrabold' : 'text-neutral-400 hover:text-white' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Manajemen Pesanan</span>
            </a>

            <a href="<?= base_url('admin/categories.php') ?>" class="flex items-center space-x-3 px-4 py-3 border border-transparent hover:border-neutral-700 <?= $current_page === 'categories.php' ? 'bg-white text-black font-extrabold' : 'text-neutral-400 hover:text-white' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"/></svg>
                <span>Kategori Produk</span>
            </a>

            <a href="<?= base_url('admin/banners.php') ?>" class="flex items-center space-x-3 px-4 py-3 border border-transparent hover:border-neutral-700 <?= $current_page === 'banners.php' ? 'bg-white text-black font-extrabold' : 'text-neutral-400 hover:text-white' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Banner & Promo</span>
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-neutral-800 text-[10px] text-neutral-500 uppercase">
        <p>PIDII ENGINE v2.6</p>
        <p>PHP 8.3 NATIVE CLEAN ARCHITECTURE</p>
    </div>
</aside>
