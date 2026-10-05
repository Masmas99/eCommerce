<?php
// checkout.php
$page_title = "Checkout & Alamat Pengiriman";
require_once __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    set_flash_message('error', 'Keranjang belanja Anda kosong. Silakan pilih produk terlebih dahulu.');
    header('Location: ' . base_url('products.php'));
    exit();
}

$cart_subtotal = get_cart_total();
$free_shipping_threshold = 3000000;

// Pre-fill user data if logged in
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_phone = '';
$user_address = '';

if (is_logged_in()) {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT phone, address FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $u = $stmt->fetch();
    if ($u) {
        $user_phone = $u['phone'] ?? '';
        $user_address = $u['address'] ?? '';
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12" x-data="{ courierCost: <?= $cart_subtotal >= $free_shipping_threshold ? 0 : 25000 ?>, subtotal: <?= $cart_subtotal ?> }">
    
    <!-- Page Header & Steps Indicator -->
    <div class="border-b border-black pb-4 mb-6 sm:mb-8 reveal">
        <div class="text-[10px] sm:text-xs uppercase font-bold text-gray-400 space-x-2 mb-1">
            <a href="<?= base_url('cart.php') ?>" class="hover:text-black">&larr; Keranjang</a>
            <span>/</span>
            <span class="text-black">Form Checkout</span>
        </div>
        <h1 class="text-xl sm:text-3xl font-black uppercase tracking-wider">CHECKOUT & ALAMAT PENGIRIMAN</h1>
        <p class="text-[11px] sm:text-xs text-gray-500 mt-1 uppercase tracking-widest">Lengkapi Data Pemesan & Pilih Metode Pembayaran</p>
    </div>

    <form action="<?= base_url('actions/checkout_action.php') ?>" method="POST">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-12 items-start">
            
            <!-- Left 2 Cols: Customer & Shipping Info (Responsive stack) -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8 reveal-left">
                
                <!-- Panel 1: Customer Details -->
                <div class="bg-white border border-black p-4 sm:p-6 space-y-4 sm:space-y-6 card-lift">
                    <div class="flex items-center space-x-3 border-b border-black pb-3">
                        <span class="w-6 h-6 bg-black text-white text-xs font-black rounded-full flex items-center justify-center">1</span>
                        <h2 class="text-xs sm:text-sm font-black uppercase tracking-widest text-black">Data Pemesan & Kontak</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="space-y-1">
                            <label class="text-[11px] sm:text-xs font-bold uppercase tracking-wider block text-gray-700">Nama Lengkap *</label>
                            <input type="text" name="customer_name" value="<?= htmlspecialchars($user_name) ?>" required placeholder="Contoh: Budi Santoso" class="w-full border border-black p-2.5 sm:p-3 text-xs focus:outline-none focus:ring-1 focus:ring-black font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] sm:text-xs font-bold uppercase tracking-wider block text-gray-700">Alamat Email *</label>
                            <input type="email" name="customer_email" value="<?= htmlspecialchars($user_email) ?>" required placeholder="email@domain.com" class="w-full border border-black p-2.5 sm:p-3 text-xs focus:outline-none focus:ring-1 focus:ring-black font-medium">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] sm:text-xs font-bold uppercase tracking-wider block text-gray-700">Nomor Telepon / WhatsApp *</label>
                        <input type="tel" name="customer_phone" value="<?= htmlspecialchars($user_phone) ?>" required placeholder="081234567890" class="w-full border border-black p-2.5 sm:p-3 text-xs focus:outline-none focus:ring-1 focus:ring-black font-medium">
                    </div>
                </div>

                <!-- Panel 2: Shipping Address & Courier -->
                <div class="bg-white border border-black p-4 sm:p-6 space-y-4 sm:space-y-6 card-lift">
                    <div class="flex items-center space-x-3 border-b border-black pb-3">
                        <span class="w-6 h-6 bg-black text-white text-xs font-black rounded-full flex items-center justify-center">2</span>
                        <h2 class="text-xs sm:text-sm font-black uppercase tracking-widest text-black">Alamat Lengkap & Kurir Pengiriman</h2>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[11px] sm:text-xs font-bold uppercase tracking-wider block text-gray-700">Alamat Pengiriman Lengkap *</label>
                        <textarea name="shipping_address" rows="3" required placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos" class="w-full border border-black p-2.5 sm:p-3 text-xs focus:outline-none focus:ring-1 focus:ring-black font-medium leading-relaxed"><?= htmlspecialchars($user_address) ?></textarea>
                        <p class="text-[10px] text-gray-500 italic">Pastikan alamat terisi lengkap untuk mempercepat proses kurir.</p>
                    </div>

                    <div class="space-y-3 pt-2">
                        <label class="text-[11px] sm:text-xs font-bold uppercase tracking-wider block text-gray-700">Pilih Kurir Pengiriman *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="border border-black p-3.5 sm:p-4 flex items-start space-x-3 cursor-pointer hover:bg-neutral-50 transition-colors">
                                <input type="radio" name="courier" value="JNE Express (REG)" @change="courierCost = <?= $cart_subtotal >= $free_shipping_threshold ? 0 : 25000 ?>" checked class="mt-1 accent-black">
                                <div class="text-xs space-y-0.5">
                                    <span class="font-extrabold uppercase block text-black">JNE Express (REG)</span>
                                    <span class="text-[11px] text-gray-500 block">Estimasi 2-3 Hari Kerja</span>
                                    <span class="font-black text-black inline-block mt-1"><?= $cart_subtotal >= $free_shipping_threshold ? 'GRATIS' : 'Rp 25.000' ?></span>
                                </div>
                            </label>

                            <label class="border border-black p-3.5 sm:p-4 flex items-start space-x-3 cursor-pointer hover:bg-neutral-50 transition-colors">
                                <input type="radio" name="courier" value="SiCepat BEST (Express)" @change="courierCost = <?= $cart_subtotal >= $free_shipping_threshold ? 0 : 40000 ?>" class="mt-1 accent-black">
                                <div class="text-xs space-y-0.5">
                                    <span class="font-extrabold uppercase block text-black">SiCepat BEST (Express)</span>
                                    <span class="text-[11px] text-gray-500 block">Estimasi 1-2 Hari Kerja</span>
                                    <span class="font-black text-black inline-block mt-1"><?= $cart_subtotal >= $free_shipping_threshold ? 'GRATIS' : 'Rp 40.000' ?></span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Panel 3: Payment Method -->
                <div class="bg-white border border-black p-4 sm:p-6 space-y-4 sm:space-y-6 card-lift">
                    <div class="flex items-center space-x-3 border-b border-black pb-3">
                        <span class="w-6 h-6 bg-black text-white text-xs font-black rounded-full flex items-center justify-center">3</span>
                        <h2 class="text-xs sm:text-sm font-black uppercase tracking-widest text-black">Metode Pembayaran</h2>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="border border-black p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between cursor-pointer hover:bg-neutral-50 transition-colors gap-2">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="Bank Transfer (BCA / Mandiri)" checked class="accent-black">
                                <span class="text-xs font-extrabold uppercase text-black">Bank Transfer (BCA / Mandiri)</span>
                            </div>
                            <span class="text-[10px] font-bold bg-neutral-100 border border-gray-300 px-2 py-0.5 text-gray-600 self-start sm:self-auto">Verifikasi Otomatis</span>
                        </label>

                        <label class="border border-black p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between cursor-pointer hover:bg-neutral-50 transition-colors gap-2">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="Instant QRIS / GoPay / OVO" class="accent-black">
                                <span class="text-xs font-extrabold uppercase text-black">QRIS / E-Wallet (GoPay, OVO, ShopeePay)</span>
                            </div>
                            <span class="text-[10px] font-bold bg-neutral-100 border border-gray-300 px-2 py-0.5 text-gray-600 self-start sm:self-auto">Scan Instant QR</span>
                        </label>

                        <label class="border border-black p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between cursor-pointer hover:bg-neutral-50 transition-colors gap-2">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="Cash On Delivery (COD)" class="accent-black">
                                <span class="text-xs font-extrabold uppercase text-black">Cash On Delivery (Bayar di Tempat)</span>
                            </div>
                            <span class="text-[10px] font-bold bg-neutral-100 border border-gray-300 px-2 py-0.5 text-gray-600 self-start sm:self-auto">Bayar ke Kurir</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right Col: Order Items Summary & Final CTA (Responsive Sidebar) -->
            <div class="bg-white border border-black p-4 sm:p-6 space-y-6 reveal-right sticky top-24">
                <h2 class="text-xs sm:text-sm font-black uppercase tracking-widest border-b border-black pb-3">Ringkasan Pesanan (<?= get_cart_count() ?>)</h2>

                <!-- Items list preview with smooth scrollbar -->
                <div class="divide-y divide-gray-200 max-h-60 sm:max-h-72 overflow-y-auto pr-1">
                    <?php foreach ($cart as $item): ?>
                        <div class="py-2.5 flex items-center space-x-3 text-xs">
                            <div class="w-10 h-12 sm:w-12 sm:h-14 bg-neutral-100 border border-gray-300 overflow-hidden flex-shrink-0">
                                <img src="<?= get_image_url($item['image'], $item['title'], 100, 120) ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow min-w-0">
                                <p class="font-bold uppercase text-black truncate text-[11px] sm:text-xs"><?= htmlspecialchars($item['title']) ?></p>
                                <p class="text-[10px] text-gray-500"><?= $item['quantity'] ?>x • <?= htmlspecialchars($item['size']) ?> / <?= htmlspecialchars($item['color']) ?></p>
                            </div>
                            <div class="font-black text-black text-xs flex-shrink-0">
                                <?= format_rupiah($item['price'] * $item['quantity']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Price Breakdown -->
                <div class="border-t border-black pt-4 space-y-2 text-xs uppercase font-bold">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Barang</span>
                        <span class="text-black font-extrabold"><?= format_rupiah($cart_subtotal) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Ongkir</span>
                        <span class="text-black font-extrabold" x-text="courierCost === 0 ? 'GRATIS' : 'Rp ' + courierCost.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between text-sm sm:text-base text-black font-black">
                        <span>GRAND TOTAL</span>
                        <span x-text="'Rp ' + (subtotal + courierCost).toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <input type="hidden" name="shipping_cost" :value="courierCost">

                <div>
                    <button type="submit" class="w-full bg-black text-white py-3.5 sm:py-4 font-black text-xs uppercase tracking-[0.18em] hover:bg-neutral-800 transition-colors shadow-none btn-sweep">
                        BUAT PESANAN SEKARANG &rarr;
                    </button>
                </div>

                <div class="text-[10px] sm:text-[11px] text-gray-500 text-center space-y-1">
                    <p>🔒 Garansi Transaksi Aman & Terverifikasi</p>
                    <p>Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan PIDII Studio.</p>
                </div>
            </div>

        </div>
    </form>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
