<?php
// product-detail.php
require_once __DIR__ . '/config/config.php';

$slug = sanitize($_GET['slug'] ?? '');
$db = Database::getConnection();

$stmt = $db->prepare("SELECT p.*, c.category_name, c.slug as category_slug 
                      FROM products p 
                      JOIN categories c ON p.category_id = c.id 
                      WHERE p.slug = ?");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    set_flash_message('error', 'Produk tidak ditemukan.');
    header('Location: ' . base_url('products.php'));
    exit();
}

$page_title = $product['title'];
require_once __DIR__ . '/includes/header.php';

// Parse Sizes & Colors
$sizes = array_map('trim', explode(',', $product['sizes'] ?: 'S, M, L, XL'));
$colors = array_map('trim', explode(',', $product['colors'] ?: 'Deep Black, Off-White'));
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ selectedSize: '<?= $sizes[0] ?>', selectedColor: '<?= $colors[0] ?>', qty: 1 }">
    
    <!-- Breadcrumb -->
    <div class="text-xs uppercase font-bold text-gray-400 space-x-2 mb-8">
        <a href="<?= base_url() ?>" class="hover:text-black">Home</a>
        <span>/</span>
        <a href="<?= base_url('products.php?category=' . urlencode($product['category_slug'])) ?>" class="hover:text-black"><?= htmlspecialchars($product['category_name']) ?></a>
        <span>/</span>
        <span class="text-black"><?= htmlspecialchars($product['title']) ?></span>
    </div>

    <!-- Product Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        
        <!-- Left: Image Gallery -->
        <div class="space-y-4 reveal-left">
            <div class="aspect-[4/5] max-h-[460px] sm:max-h-[550px] bg-neutral-100 border border-black overflow-hidden relative img-zoom-container card-lift">
                <img src="<?= get_image_url($product['image'], $product['title'], 1000, 1250) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="w-full h-full object-cover">
                <?php if ($product['badge']): ?>
                    <span class="absolute top-4 left-4 bg-black text-white text-xs font-black tracking-widest px-4 py-1.5 uppercase">
                        <?= htmlspecialchars($product['badge']) ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="aspect-[4/5] bg-neutral-100 border border-black overflow-hidden cursor-pointer opacity-100">
                    <img src="<?= get_image_url($product['image'], $product['title'], 400, 500) ?>" class="w-full h-full object-cover">
                </div>
                <div class="aspect-[4/5] bg-neutral-100 border border-gray-300 overflow-hidden cursor-pointer hover:border-black transition-colors opacity-75">
                    <img src="<?= get_image_url('hero_banner_2.jpg', $product['title'] . ' Detail', 400, 500) ?>" class="w-full h-full object-cover">
                </div>
                <div class="aspect-[4/5] bg-neutral-100 border border-gray-300 overflow-hidden cursor-pointer hover:border-black transition-colors opacity-75">
                    <img src="<?= get_image_url('cat_outerwear.jpg', $product['title'] . ' Fabric', 400, 500) ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Right: Product Purchase Panel -->
        <div class="space-y-6 reveal-right">
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-1"><?= htmlspecialchars($product['category_name']) ?></span>
                <h1 class="text-3xl font-black uppercase tracking-wider text-black"><?= htmlspecialchars($product['title']) ?></h1>
                
                <div class="flex items-center space-x-4 mt-3">
                    <span class="text-2xl font-black text-black"><?= format_rupiah($product['price']) ?></span>
                    <div class="flex items-center space-x-1 text-xs font-bold bg-neutral-100 px-3 py-1 border border-gray-200">
                        <span class="text-yellow-500">★</span>
                        <span><?= htmlspecialchars($product['rating']) ?> / 5.0</span>
                    </div>
                </div>
            </div>

            <div class="border-y border-gray-200 py-4 text-xs font-medium leading-relaxed text-gray-600">
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>

            <!-- Form Add to Cart -->
            <form action="<?= base_url('actions/cart_action.php') ?>" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="size" :value="selectedSize">
                <input type="hidden" name="color" :value="selectedColor">
                <input type="hidden" name="quantity" :value="qty">

                <!-- Color Selection -->
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest flex items-center justify-between">
                        <span>Warna:</span>
                        <span class="text-gray-500" x-text="selectedColor"></span>
                    </label>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($colors as $c): ?>
                            <button type="button" @click="selectedColor = '<?= htmlspecialchars($c) ?>'"
                                    :class="selectedColor === '<?= htmlspecialchars($c) ?>' ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'"
                                    class="border px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors">
                                <?= htmlspecialchars($c) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest flex items-center justify-between">
                        <span>Ukuran (Size):</span>
                        <a href="#" class="text-gray-500 underline lowercase font-normal">panduan ukuran</a>
                    </label>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($sizes as $s): ?>
                            <button type="button" @click="selectedSize = '<?= htmlspecialchars($s) ?>'"
                                    :class="selectedSize === '<?= htmlspecialchars($s) ?>' ? 'border-black bg-black text-white' : 'border-gray-300 text-black hover:border-black'"
                                    class="border w-12 h-12 flex items-center justify-center text-xs font-bold uppercase transition-colors">
                                <?= htmlspecialchars($s) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quantity & Stock Status -->
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest block">Jumlah:</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border border-black bg-white">
                            <button type="button" @click="if (qty > 1) qty--" class="px-4 py-2 font-bold text-sm hover:bg-gray-100">&minus;</button>
                            <span class="px-6 py-2 text-xs font-extrabold" x-text="qty"></span>
                            <button type="button" @click="if (qty < <?= $product['stock'] ?>) qty++" class="px-4 py-2 font-bold text-sm hover:bg-gray-100">+</button>
                        </div>
                        <span class="text-xs text-gray-500 font-bold uppercase">Stok Tersedia: <?= $product['stock'] ?> unit</span>
                    </div>
                </div>

                <!-- CTA Add to Bag -->
                <div class="pt-4 space-y-3">
                    <?php if ($product['stock'] > 0): ?>
                        <button type="submit" class="w-full bg-black text-white py-4 font-black text-xs uppercase tracking-[0.2em] hover:bg-neutral-800 transition-colors shadow-none">
                            + TAMBAHKAN KE BAG BELANJA
                        </button>
                    <?php else: ?>
                        <button type="button" disabled class="w-full bg-gray-300 text-gray-500 py-4 font-black text-xs uppercase tracking-[0.2em] cursor-not-allowed">
                            STOK HABIS (OUT OF STOCK)
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Product Details Accordion -->
            <div class="border-t border-black pt-6 space-y-4" x-data="{ tab: 'details' }">
                <div class="border-b border-gray-200">
                    <button @click="tab = tab === 'details' ? '' : 'details'" class="w-full text-left font-bold text-xs uppercase tracking-widest py-3 flex justify-between items-center">
                        <span>Spesifikasi & Detail Produk</span>
                        <span x-text="tab === 'details' ? '&minus;' : '+'"></span>
                    </button>
                    <div x-show="tab === 'details'" x-transition class="pb-4 text-xs text-gray-600 space-y-2">
                        <p>✦ 100% Guaranteed Original Craftsmanship</p>
                        <p>✦ Fit: Architectural Minimalist Boxy Silhouette</p>
                        <p>✦ Care: Dry Clean Only</p>
                    </div>
                </div>

                <div class="border-b border-gray-200">
                    <button @click="tab = tab === 'shipping' ? '' : 'shipping'" class="w-full text-left font-bold text-xs uppercase tracking-widest py-3 flex justify-between items-center">
                        <span>Informasi Pengiriman & Retur</span>
                        <span x-text="tab === 'shipping' ? '&minus;' : '+'"></span>
                    </button>
                    <div x-show="tab === 'shipping'" x-transition class="pb-4 text-xs text-gray-600 space-y-2">
                        <p>✦ Gratis ongkir untuk pesanan di atas Rp 3.000.000.</p>
                        <p>✦ Pengiriman menggunakan JNE / SiCepat dengan nomor resi dilacak secara real-time.</p>
                        <p>✦ Garansi pengembalian atau tukar size dalam 14 hari kerja.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
