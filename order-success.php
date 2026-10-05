<?php
// order-success.php
$page_title = "Pesanan Berhasil dibuat";
require_once __DIR__ . '/includes/header.php';

$order_number = sanitize($_GET['order_number'] ?? '');
$db = Database::getConnection();

$stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ?");
$stmt->execute([$order_number]);
$order = $stmt->fetch();

if (!$order) {
    set_flash_message('error', 'Pesanan tidak ditemukan.');
    header('Location: ' . base_url('products.php'));
    exit();
}

// Fetch Order Items
$item_stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
$item_stmt->execute([$order['id']]);
$order_items = $item_stmt->fetchAll();
?>

<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="bg-white border-2 border-black p-8 sm:p-12 space-y-8">
        
        <!-- Top Status Banner -->
        <div class="text-center space-y-3 border-b border-black pb-8">
            <div class="inline-block bg-black text-white p-3 rounded-full mb-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block">PEMESANAN BERHASIL</span>
            <h1 class="text-3xl font-black uppercase tracking-wider">TERIMA KASIH ATAS PESANAN ANDA</h1>
            <p class="text-xs text-gray-600">Nomor Pesanan: <strong class="text-black font-extrabold text-sm"><?= htmlspecialchars($order['order_number']) ?></strong></p>
        </div>

        <!-- Payment Instructions Box -->
        <div class="bg-neutral-50 border border-black p-6 space-y-4">
            <h2 class="text-xs font-black uppercase tracking-widest border-b border-gray-300 pb-2">Instruksi Pembayaran (<?= htmlspecialchars($order['payment_method']) ?>)</h2>
            <div class="text-xs space-y-2 text-gray-700 font-medium">
                <p>Silakan lakukan transfer sesuai total tagihan ke rekening berikut:</p>
                <div class="bg-white p-4 border border-gray-300 font-mono text-sm space-y-1">
                    <p class="text-xs text-gray-500 font-sans uppercase">Bank BCA - Pidii Studio Indonesia</p>
                    <p class="font-extrabold text-black">8830-1928-3741</p>
                    <p class="text-xs text-gray-500 font-sans">Total Tagihan: <strong class="text-black font-bold"><?= format_rupiah($order['grand_total']) ?></strong></p>
                </div>
                <p class="text-[11px] text-gray-500 italic">Pesanan akan otomatis diproses setelah pembayaran terverifikasi.</p>
            </div>
        </div>

        <!-- Order Summary & Shipping Address -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs uppercase font-bold">
            <div class="border border-gray-200 p-4 space-y-2">
                <span class="text-gray-400 text-[10px]">Alamat Pengiriman</span>
                <p class="text-black font-extrabold"><?= htmlspecialchars($order['customer_name']) ?></p>
                <p class="text-gray-600 font-normal normal-case"><?= htmlspecialchars($order['customer_phone']) ?></p>
                <p class="text-gray-600 font-normal normal-case"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
            </div>
            
            <div class="border border-gray-200 p-4 space-y-2">
                <span class="text-gray-400 text-[10px]">Status & Kurir</span>
                <p>Status: <span class="bg-black text-white px-2 py-0.5 text-[10px] uppercase"><?= htmlspecialchars($order['status']) ?></span></p>
                <p class="text-gray-600">Kurir: <?= htmlspecialchars($order['courier']) ?></p>
                <p class="text-gray-600">Nomor Resi: <strong class="text-black"><?= $order['tracking_number'] ?: 'Belum diinput (Pending Dispatch)' ?></strong></p>
            </div>
        </div>

        <!-- Purchased Items Table -->
        <div class="space-y-4">
            <h3 class="text-xs font-black uppercase tracking-widest border-b border-black pb-2">Rincian Barang</h3>
            <div class="divide-y divide-gray-200 border border-gray-200">
                <?php foreach ($order_items as $item): ?>
                    <div class="p-4 flex justify-between items-center text-xs">
                        <div>
                            <p class="font-bold uppercase text-black"><?= htmlspecialchars($item['product_title']) ?></p>
                            <p class="text-gray-500 font-normal">Ukuran: <?= htmlspecialchars($item['size']) ?> | Warna: <?= htmlspecialchars($item['color']) ?> | Qty: <?= $item['quantity'] ?></p>
                        </div>
                        <div class="font-black text-black">
                            <?= format_rupiah($item['subtotal']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-black pt-6">
            <a href="<?= base_url('products.php') ?>" class="text-xs font-bold uppercase tracking-widest underline hover:text-gray-500">
                &larr; Kembali Berbelanja
            </a>
            <a href="<?= base_url('my-orders.php') ?>" class="bg-black text-white px-6 py-3 text-xs font-black uppercase tracking-widest hover:bg-neutral-800 transition-colors">
                Lacak Status Pesanan &rarr;
            </a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
