<?php
// cart.php
$page_title = "Keranjang Belanja";
require_once __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$cart_subtotal = get_cart_total();
$free_shipping_threshold = 3000000;
$remaining_for_free_shipping = max(0, $free_shipping_threshold - $cart_subtotal);
$shipping_progress = min(100, ($cart_subtotal / $free_shipping_threshold) * 100);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="border-b border-black pb-4 mb-8">
        <h1 class="text-3xl font-black uppercase tracking-wider">KERANJANG BELANJA (<?= get_cart_count() ?>)</h1>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Minimalist Shopping Bag</p>
    </div>

    <?php if (empty($cart)): ?>
        <div class="bg-white border border-black p-16 text-center space-y-6">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <div class="space-y-2">
                <h2 class="text-xl font-bold uppercase tracking-wider">Keranjang Belanja Anda Kosong</h2>
                <p class="text-xs text-gray-500 max-w-md mx-auto">Jelajahi koleksi busana modern minimalist kami dan tambahkan item impian Anda ke dalam keranjang.</p>
            </div>
            <div>
                <a href="<?= base_url('products.php') ?>" class="inline-block bg-black text-white px-8 py-4 text-xs font-black uppercase tracking-widest hover:bg-neutral-800 transition-colors">
                    JELAJAHI KOLEKSI &rarr;
                </a>
            </div>
        </div>
    <?php else: ?>

        <!-- Free Shipping Threshold Indicator -->
        <div class="bg-white border border-black p-5 mb-8 space-y-2">
            <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                <?php if ($remaining_for_free_shipping > 0): ?>
                    <span>Tambahkan <span class="text-black font-extrabold"><?= format_rupiah($remaining_for_free_shipping) ?></span> lagi untuk Gratis Ongkir!</span>
                <?php else: ?>
                    <span class="text-emerald-700">✦ Selamat! Anda Berhak Mendapatkan Gratis Ongkir!</span>
                <?php endif; ?>
                <span><?= number_format($shipping_progress, 0) ?>%</span>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded-none overflow-hidden">
                <div class="bg-black h-2 transition-all duration-500" style="width: <?= $shipping_progress ?>%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            
            <!-- Items Table List -->
            <div class="lg:col-span-2 space-y-6 reveal-left">
                <div class="bg-white border border-black divide-y divide-gray-200">
                    <?php foreach ($cart as $item): ?>
                        <div class="p-6 flex flex-col sm:flex-row items-center gap-6">
                            <!-- Image -->
                            <div class="w-24 h-32 bg-neutral-100 border border-gray-300 overflow-hidden flex-shrink-0">
                                <img src="<?= get_image_url($item['image'], $item['title'], 200, 250) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover">
                            </div>

                            <!-- Details -->
                            <div class="flex-grow space-y-2 text-center sm:text-left">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-black">
                                    <?= htmlspecialchars($item['title']) ?>
                                </h3>
                                <div class="text-xs text-gray-500 space-x-3 uppercase">
                                    <span>Size: <strong class="text-black"><?= htmlspecialchars($item['size']) ?></strong></span>
                                    <span>•</span>
                                    <span>Color: <strong class="text-black"><?= htmlspecialchars($item['color']) ?></strong></span>
                                </div>
                                <div class="text-sm font-black text-black">
                                    <?= format_rupiah($item['price']) ?>
                                </div>
                            </div>

                            <!-- Quantity controls & Subtotal -->
                            <div class="flex flex-col items-center sm:items-end space-y-3">
                                <form action="<?= base_url('actions/cart_action.php') ?>" method="POST" class="flex items-center border border-black bg-white">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="item_key" value="<?= $item['key'] ?>">
                                    <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>" class="px-3 py-1 font-bold text-xs hover:bg-gray-100">&minus;</button>
                                    <span class="px-4 py-1 text-xs font-black"><?= $item['quantity'] ?></span>
                                    <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="px-3 py-1 font-bold text-xs hover:bg-gray-100">+</button>
                                </form>

                                <div class="text-right">
                                    <span class="text-xs text-gray-400 block uppercase">Subtotal</span>
                                    <span class="text-sm font-black text-black"><?= format_rupiah($item['price'] * $item['quantity']) ?></span>
                                </div>

                                <a href="<?= base_url('actions/cart_action.php?action=remove&item_key=' . urlencode($item['key'])) ?>" class="text-[11px] text-red-600 underline font-bold uppercase hover:text-black">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-between items-center text-xs font-bold uppercase">
                    <a href="<?= base_url('products.php') ?>" class="underline hover:text-gray-500">&larr; Lanjutkan Belanja</a>
                    <a href="<?= base_url('actions/cart_action.php?action=clear') ?>" onclick="return confirm('Kosongkan keranjang belanja?')" class="text-gray-500 hover:text-red-600 underline">Kosongkan Bag</a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="bg-white border border-black p-6 space-y-6 reveal-right">
                <h2 class="text-sm font-black uppercase tracking-widest border-b border-black pb-3">Ringkasan Biaya</h2>

                <div class="space-y-3 text-xs uppercase font-bold">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Barang</span>
                        <span class="text-black font-extrabold"><?= format_rupiah($cart_subtotal) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Estimasi Pengiriman</span>
                        <span class="text-black font-extrabold"><?= $cart_subtotal >= $free_shipping_threshold ? 'GRATIS' : format_rupiah(25000) ?></span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between text-sm text-black font-black">
                        <span>TOTAL ESTAMASI</span>
                        <span><?= format_rupiah($cart_subtotal + ($cart_subtotal >= $free_shipping_threshold ? 0 : 25000)) ?></span>
                    </div>
                </div>

                <div>
                    <a href="<?= base_url('checkout.php') ?>" class="block w-full bg-black text-white text-center py-4 font-black text-xs uppercase tracking-[0.2em] hover:bg-neutral-800 transition-colors">
                        LANJUT KE CHECKOUT &rarr;
                    </a>
                </div>

                <div class="text-[11px] text-gray-500 text-center space-y-1">
                    <p>🔒 Pembayaran Aman 100% Terenskripsi</p>
                    <p>Pengiriman via JNE, SiCepat & Pos Indonesia</p>
                </div>
            </div>

        </div>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
