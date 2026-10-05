<?php
// my-orders.php
$page_title = "Riwayat & Status Pesanan";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

$orders = [];
$user_id = $_SESSION['user_id'] ?? null;
$search_order = sanitize($_GET['order_number'] ?? '');

if ($user_id) {
    $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} elseif ($search_order) {
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ? ORDER BY id DESC");
    $stmt->execute([$search_order]);
    $orders = $stmt->fetchAll();
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="border-b border-black pb-4 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black uppercase tracking-wider">RIWAYAT & STATUS PESANAN</h1>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Pantau Pengiriman & Riwayat Pembelian Anda</p>
        </div>

        <!-- Quick Track Order Input for Guest -->
        <form method="GET" action="<?= base_url('my-orders.php') ?>" class="flex gap-2">
            <input type="text" name="order_number" value="<?= htmlspecialchars($search_order) ?>" placeholder="Cari Nomor Pesanan (Contoh: FTR-...)" class="border border-black p-2 text-xs font-medium w-64 focus:outline-none">
            <button type="submit" class="bg-black text-white px-4 py-2 text-xs font-bold uppercase tracking-wider">Lacak</button>
        </form>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-white border border-black p-12 text-center space-y-4">
            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-lg font-bold uppercase tracking-wider">Belum Ada Pesanan Ditemukan</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                <?= !is_logged_in() ? 'Silakan login ke akun Anda atau gunakan fitur pencarian nomor pesanan di atas.' : 'Anda belum pernah melakukan pemesanan.' ?>
            </p>
            <div>
                <a href="<?= base_url('products.php') ?>" class="inline-block bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest">Mulai Berbelanja</a>
            </div>
        </div>
    <?php else: ?>

        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <?php
                    // Fetch items
                    $stmt_item = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
                    $stmt_item->execute([$order['id']]);
                    $items = $stmt_item->fetchAll();

                    $statusBadgeClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                    if ($order['status'] === 'Paid') $statusBadgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
                    if ($order['status'] === 'Shipped') $statusBadgeClass = 'bg-purple-100 text-purple-800 border-purple-300';
                    if ($order['status'] === 'Completed') $statusBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                    if ($order['status'] === 'Cancelled') $statusBadgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
                ?>
                <div class="bg-white border border-black p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-4 gap-2 text-xs uppercase font-bold">
                        <div>
                            <span class="text-gray-400 block text-[10px]">Nomor Pesanan</span>
                            <span class="text-black font-extrabold text-sm"><?= htmlspecialchars($order['order_number']) ?></span>
                            <span class="text-gray-400 font-normal text-[11px] block mt-0.5"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 text-xs border font-extrabold rounded-none uppercase <?= $statusBadgeClass ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Items list -->
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($items as $item): ?>
                            <div class="py-3 flex justify-between items-center text-xs">
                                <div>
                                    <p class="font-bold text-black uppercase"><?= htmlspecialchars($item['product_title']) ?></p>
                                    <p class="text-gray-500 font-normal">Ukuran: <?= htmlspecialchars($item['size']) ?> • Warna: <?= htmlspecialchars($item['color']) ?> • Qty: <?= $item['quantity'] ?></p>
                                </div>
                                <div class="font-black text-black">
                                    <?= format_rupiah($item['subtotal']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Footer Details & Resi -->
                    <div class="border-t border-black pt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs font-bold">
                        <div class="space-y-1">
                            <p class="text-gray-500">Kurir Pengiriman: <span class="text-black"><?= htmlspecialchars($order['courier']) ?></span></p>
                            <p class="text-gray-500">Nomor Resi / Tracking: 
                                <?php if ($order['tracking_number']): ?>
                                    <span class="bg-black text-white px-2 py-0.5 font-mono text-[11px] uppercase tracking-wider"><?= htmlspecialchars($order['tracking_number']) ?></span>
                                <?php else: ?>
                                    <span class="text-neutral-400 italic">Sedang Diproses</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-500 block text-[10px]">Grand Total</span>
                            <span class="text-base font-black text-black"><?= format_rupiah($order['grand_total']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
