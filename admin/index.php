<?php
// admin/index.php
$page_title = "Dashboard Overview";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Calculate Stats
$total_sales = $db->query("SELECT SUM(grand_total) FROM orders WHERE status IN ('Paid', 'Shipped', 'Completed')")->fetchColumn() ?: 0.0;
$new_orders_count = $db->query("SELECT COUNT(*) FROM orders WHERE status IN ('Pending', 'Paid')")->fetchColumn() ?: 0;
$total_products = $db->query("SELECT COUNT(*) FROM products")->fetchColumn() ?: 0;
$total_customers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn() ?: 0;

// Recent Orders
$recent_orders = $db->query("SELECT * FROM orders ORDER BY id DESC LIMIT 6")->fetchAll();
?>

<!-- Overview Header -->
<div class="border-b border-neutral-800 pb-4">
    <h1 class="text-2xl font-black uppercase tracking-wider text-white">DASHBOARD OVERVIEW</h1>
    <p class="text-xs text-neutral-400 mt-1 uppercase tracking-widest">Ringkasan Statistik & Performa Toko</p>
</div>

<!-- 4 Key Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Stat 1: Total Sales -->
    <div class="bg-neutral-900 border border-neutral-800 p-6 space-y-2">
        <div class="flex justify-between items-center text-neutral-400 text-xs font-mono font-bold uppercase">
            <span>Total Penjualan</span>
            <span class="text-emerald-400">✦ REVENUE</span>
        </div>
        <div class="text-2xl font-black text-white">
            <?= format_rupiah($total_sales) ?>
        </div>
        <p class="text-[10px] text-neutral-500">Omset terakumulasi dari pesanan terverifikasi</p>
    </div>

    <!-- Stat 2: New Orders -->
    <div class="bg-neutral-900 border border-neutral-800 p-6 space-y-2">
        <div class="flex justify-between items-center text-neutral-400 text-xs font-mono font-bold uppercase">
            <span>Pesanan Masuk</span>
            <span class="text-blue-400">✦ ORDERS</span>
        </div>
        <div class="text-2xl font-black text-white">
            <?= number_format($new_orders_count) ?> <span class="text-xs font-normal text-neutral-400">Pesanan</span>
        </div>
        <p class="text-[10px] text-neutral-500">Perlu tindakan atau pengiriman resi</p>
    </div>

    <!-- Stat 3: Total Products -->
    <div class="bg-neutral-900 border border-neutral-800 p-6 space-y-2">
        <div class="flex justify-between items-center text-neutral-400 text-xs font-mono font-bold uppercase">
            <span>Total Produk</span>
            <span class="text-purple-400">✦ CATALOG</span>
        </div>
        <div class="text-2xl font-black text-white">
            <?= number_format($total_products) ?> <span class="text-xs font-normal text-neutral-400">SKU</span>
        </div>
        <p class="text-[10px] text-neutral-500">Item terdaftar dalam database</p>
    </div>

    <!-- Stat 4: Customers -->
    <div class="bg-neutral-900 border border-neutral-800 p-6 space-y-2">
        <div class="flex justify-between items-center text-neutral-400 text-xs font-mono font-bold uppercase">
            <span>Total Pelanggan</span>
            <span class="text-yellow-400">✦ USERS</span>
        </div>
        <div class="text-2xl font-black text-white">
            <?= number_format($total_customers) ?> <span class="text-xs font-normal text-neutral-400">User</span>
        </div>
        <p class="text-[10px] text-neutral-500">Pelanggan terdaftar dalam sistem</p>
    </div>

</div>

<!-- Recent Orders Table Section -->
<div class="bg-neutral-900 border border-neutral-800 p-6 space-y-6">
    <div class="flex justify-between items-center border-b border-neutral-800 pb-4">
        <div>
            <h2 class="text-sm font-black uppercase tracking-widest text-white">PESANAN TERBARU MASUK</h2>
            <p class="text-[11px] text-neutral-400">Daftar 6 transaksi paling akhir</p>
        </div>
        <a href="<?= base_url('admin/orders.php') ?>" class="text-xs font-mono font-bold uppercase text-neutral-400 hover:text-white underline">
            Lihat Semua Pesanan &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs uppercase font-mono border-collapse">
            <thead>
                <tr class="border-b border-neutral-800 text-neutral-400 font-bold">
                    <th class="py-3 px-4">No. Order</th>
                    <th class="py-3 px-4">Pelanggan</th>
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Grand Total</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-800 text-neutral-200">
                <?php if (empty($recent_orders)): ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-neutral-500 font-sans">Belum ada pesanan masuk.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_orders as $ord): ?>
                        <tr class="hover:bg-neutral-800/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-white"><?= htmlspecialchars($ord['order_number']) ?></td>
                            <td class="py-3 px-4 font-sans font-medium text-neutral-300">
                                <?= htmlspecialchars($ord['customer_name']) ?>
                                <span class="block text-[10px] text-neutral-500 lowercase"><?= htmlspecialchars($ord['customer_email']) ?></span>
                            </td>
                            <td class="py-3 px-4 text-neutral-400"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                            <td class="py-3 px-4 font-bold text-white"><?= format_rupiah($ord['grand_total']) ?></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 text-[10px] font-bold border border-neutral-700 bg-neutral-800 text-neutral-200">
                                    <?= htmlspecialchars($ord['status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="<?= base_url('admin/orders.php') ?>" class="bg-white text-black px-3 py-1 text-[10px] font-bold uppercase hover:bg-neutral-200">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
