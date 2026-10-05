<?php
// index.php
$page_title = "Modern Minimalist / High-Fashion E-Commerce";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Fetch Active Banners
$banners = $db->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY id DESC")->fetchAll();

// Fetch Categories
$categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();

// Fetch Featured / Latest Products
$products = $db->query("SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 8")->fetchAll();
?>

<!-- HERO BANNER SECTION (Compact & Responsive Height) -->
<section class="relative bg-black text-white overflow-hidden" 
         x-data="{ 
            activeSlide: 0, 
            totalSlides: <?= count($banners) ?>,
            timer: null,
            startAutoplay() {
                if (this.totalSlides > 1) {
                    this.timer = setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                    }, 4500);
                }
            },
            stopAutoplay() {
                if (this.timer) clearInterval(this.timer);
            }
         }"
         x-init="startAutoplay()"
         @mouseenter="stopAutoplay()"
         @mouseleave="startAutoplay()">
    <?php if (!empty($banners)): ?>
        <div class="relative h-[48vh] sm:h-[58vh] md:h-[65vh] max-h-[520px] min-h-[340px] w-full overflow-hidden">
            <?php foreach ($banners as $idx => $banner): ?>
                <div x-show="activeSlide === <?= $idx ?>" 
                     x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-800"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 bg-cover bg-center flex items-center justify-center text-center p-4 sm:p-6 hero-zoom-bg"
                     style="background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.75)), url('<?= get_image_url($banner['image'], $banner['title'], 1920, 1080) ?>');">
                    
                    <div class="max-w-3xl space-y-3 sm:space-y-6 transform transition-all duration-1000 px-2">
                        <span class="inline-block bg-white text-black font-extrabold text-[9px] sm:text-xs uppercase tracking-[0.25em] px-3 py-1 rounded-none shadow-md animate-pulse">AUTUMN / WINTER 2026</span>
                        <h1 class="text-2xl sm:text-5xl lg:text-6xl font-black uppercase tracking-[0.12em] leading-tight serif-title drop-shadow-lg">
                            <?= htmlspecialchars($banner['title']) ?>
                        </h1>
                        <p class="text-xs sm:text-base text-neutral-300 font-light max-w-xl mx-auto tracking-wide line-clamp-2">
                            <?= htmlspecialchars($banner['subtitle']) ?>
                        </p>
                        <div class="pt-2 sm:pt-4">
                            <a href="<?= htmlspecialchars($banner['link']) ?>" class="inline-block bg-white text-black font-extrabold text-[11px] sm:text-xs uppercase tracking-[0.2em] px-6 sm:px-9 py-3 sm:py-4 border-2 border-white hover:bg-black hover:text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg btn-sweep">
                                EXPLORE COLLECTION &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (count($banners) > 1): ?>
                <!-- Carousel Controls & Progress Indicator -->
                <div class="absolute bottom-4 sm:bottom-8 left-0 right-0 flex items-center justify-center space-x-2 sm:space-x-3 z-20">
                    <?php foreach ($banners as $idx => $b): ?>
                        <button @click="activeSlide = <?= $idx ?>" 
                                :class="activeSlide === <?= $idx ?> ? 'bg-white w-8 sm:w-12 h-1.5 sm:h-2' : 'bg-white/40 w-3 sm:w-4 h-1 sm:h-1.5 hover:bg-white/80'" 
                                class="transition-all duration-500 rounded-none focus:outline-none relative overflow-hidden">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<!-- BRAND PHILOSOPHY STRIP -->
<section class="border-y border-gray-200 bg-white py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 text-center uppercase tracking-widest text-xs reveal">
        <div class="space-y-1.5 p-2 sm:p-4 transition-transform duration-300 hover:-translate-y-1">
            <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 flex items-center justify-center bg-black text-white rounded-full text-xs sm:text-base">✦</div>
            <h4 class="font-black text-black">Architectural Tailoring</h4>
            <p class="text-gray-500 text-[11px] normal-case leading-relaxed">Dibuat secara teliti dengan struktur tajam dan potongan minimalis tanpa kompromi.</p>
        </div>
        <div class="space-y-1.5 md:border-x border-gray-200 p-2 sm:p-4 transition-transform duration-300 hover:-translate-y-1">
            <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 flex items-center justify-center bg-black text-white rounded-full text-xs sm:text-base">❖</div>
            <h4 class="font-black text-black">Sustainable Raw Materials</h4>
            <p class="text-gray-500 text-[11px] normal-case leading-relaxed">Wool premium, organic raw silk, dan calfskin leather dari pengrajin bersertifikasi.</p>
        </div>
        <div class="space-y-1.5 p-2 sm:p-4 transition-transform duration-300 hover:-translate-y-1">
            <div class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 flex items-center justify-center bg-black text-white rounded-full text-xs sm:text-base">⚡</div>
            <h4 class="font-black text-black">Express Worldwide Dispatch</h4>
            <p class="text-gray-500 text-[11px] normal-case leading-relaxed">Pengiriman cepat, aman, dan garansi pengembalian 14 hari penuh.</p>
        </div>
    </div>
</section>

<!-- SHOP BY CATEGORY - RESPONSIVE COMPACT GRID (2 Cols on Mobile) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
    <div class="flex items-end justify-between mb-8 sm:mb-12 border-b border-black pb-4 reveal">
        <div>
            <span class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest block">Struktur Kategori</span>
            <h2 class="text-xl sm:text-3xl lg:text-4xl font-black uppercase tracking-wider">SHOP BY CATEGORY</h2>
        </div>
        <a href="<?= base_url('products.php') ?>" class="text-[11px] sm:text-xs font-bold uppercase tracking-widest underline hover:text-gray-500 transition-colors">Lihat Semua (<?= count($categories) ?>) &rarr;</a>
    </div>

    <!-- Responsive Grid: 2 columns on Mobile, 4 on Desktop -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        <?php foreach ($categories as $index => $cat): ?>
            <?php 
                $spanClass = ($index === 0) ? 'col-span-2 sm:col-span-2 md:row-span-2 h-52 sm:h-80 md:h-full' : 'h-44 sm:h-64 lg:h-72';
            ?>
            <a href="<?= base_url('products.php?category=' . urlencode($cat['slug'])) ?>" class="group relative overflow-hidden bg-black text-white border border-gray-200 img-zoom-container card-lift reveal <?= $spanClass ?> block">
                <img src="<?= get_image_url($cat['image'], $cat['category_name'], 600, 600) ?>" alt="<?= htmlspecialchars($cat['category_name']) ?>" class="w-full h-full object-cover opacity-80 group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent flex flex-col justify-end p-3 sm:p-6 transition-opacity group-hover:opacity-95">
                    <span class="text-[9px] sm:text-[10px] font-bold tracking-[0.2em] text-neutral-400 uppercase">CATEGORY 0<?= $index + 1 ?></span>
                    <h3 class="text-sm sm:text-xl font-extrabold uppercase tracking-wide group-hover:underline underline-offset-4 transform group-hover:-translate-y-1 transition-transform line-clamp-1">
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </h3>
                    <span class="text-[10px] sm:text-xs font-semibold text-neutral-300 mt-1 uppercase tracking-widest flex items-center space-x-1 group-hover:translate-x-2 transition-transform duration-300">
                        <span>Jelajahi</span>
                        <span>&rarr;</span>
                    </span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- CATALOG & PRODUCT GRID (2 Cols on Mobile, Sleek Sizing) -->
<section class="bg-white py-12 sm:py-20 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-12 border-b border-black pb-4 reveal">
            <div>
                <span class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-widest block">Koleksi Teratas</span>
                <h2 class="text-xl sm:text-3xl lg:text-4xl font-black uppercase tracking-wider">NEW ARRIVALS & LIMITED RELEASES</h2>
            </div>
            <div class="mt-3 md:mt-0 flex space-x-4 text-[11px] sm:text-xs font-bold uppercase tracking-wider">
                <a href="<?= base_url('products.php') ?>" class="py-1 border-b-2 border-black">Semua</a>
                <a href="<?= base_url('products.php?badge=Limited+Edition') ?>" class="py-1 text-gray-500 hover:text-black transition-colors">Limited Edition</a>
                <a href="<?= base_url('products.php?badge=Sale') ?>" class="py-1 text-gray-500 hover:text-black transition-colors">Sale</a>
            </div>
        </div>

        <!-- Product Cards Grid: 2 columns on Mobile! -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <?php foreach ($products as $prod): ?>
                <div class="group flex flex-col justify-between bg-white border border-gray-200 card-lift reveal">
                    <div>
                        <!-- Product Image & Badge (Compact Aspect Ratio) -->
                        <div class="relative overflow-hidden aspect-[4/5] max-h-64 sm:max-h-80 bg-neutral-100 img-zoom-container">
                            <a href="<?= base_url('product-detail.php?slug=' . urlencode($prod['slug'])) ?>">
                                <img src="<?= get_image_url($prod['image'], $prod['title'], 600, 750) ?>" alt="<?= htmlspecialchars($prod['title']) ?>" class="w-full h-full object-cover">
                            </a>

                            <?php if (!empty($prod['badge'])): ?>
                                <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-black text-white text-[9px] sm:text-[10px] font-black tracking-widest px-2 py-0.5 sm:px-3 sm:py-1 uppercase rounded-none shadow-md">
                                    <?= htmlspecialchars($prod['badge']) ?>
                                </span>
                            <?php endif; ?>

                            <!-- Rating Badge -->
                            <div class="absolute bottom-2 right-2 sm:bottom-3 sm:right-3 bg-white/90 backdrop-blur text-black text-[10px] sm:text-[11px] font-bold px-1.5 py-0.5 sm:px-2.5 sm:py-1 flex items-center space-x-1 border border-gray-200 shadow-sm">
                                <span class="text-yellow-500 text-[10px]">★</span>
                                <span><?= htmlspecialchars($prod['rating']) ?></span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-3 sm:p-4 space-y-1 sm:space-y-2">
                            <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest block truncate"><?= htmlspecialchars($prod['category_name']) ?></span>
                            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-black line-clamp-1 group-hover:underline">
                                <a href="<?= base_url('product-detail.php?slug=' . urlencode($prod['slug'])) ?>">
                                    <?= htmlspecialchars($prod['title']) ?>
                                </a>
                            </h3>
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-xs sm:text-sm font-extrabold text-black"><?= format_rupiah($prod['price']) ?></span>
                                <span class="text-[10px] sm:text-[11px] text-gray-500 hidden sm:inline">Stok: <?= $prod['stock'] ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Add Button -->
                    <div class="p-3 sm:p-4 pt-0">
                        <form action="<?= base_url('actions/cart_action.php') ?>" method="POST">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-black text-white py-2.5 sm:py-3 text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors flex items-center justify-center space-x-2 btn-sweep">
                                <span>+ BAG</span>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- EDITORIAL BANNER -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 reveal">
    <div class="bg-black text-white p-6 sm:p-12 md:p-16 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center border border-neutral-800 card-lift">
        <div class="space-y-4 sm:space-y-6">
            <span class="text-[10px] sm:text-xs font-bold text-neutral-400 uppercase tracking-[0.3em]">EDITORIAL LOOKBOOK</span>
            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black uppercase tracking-wider leading-tight serif-title">UNCOMPROMISING MINIMALISM</h2>
            <p class="text-xs sm:text-sm text-neutral-300 leading-relaxed font-light">
                Setiap bagian dirancang dengan presisi geometri tanpa ornamen berlebih. Menghadirkan esensi kenyamanan dan ketahanan material tinggi untuk gaya hidup urban kontemporer.
            </p>
            <div>
                <a href="<?= base_url('products.php') ?>" class="inline-block bg-white text-black font-extrabold text-[11px] sm:text-xs uppercase tracking-widest px-6 sm:px-8 py-3.5 sm:py-4 hover:bg-neutral-200 transition-all duration-300 transform hover:scale-105">
                    VIEW EDITORIAL &rarr;
                </a>
            </div>
        </div>
        <div class="aspect-[16/10] sm:aspect-[4/3] max-h-80 bg-neutral-900 overflow-hidden border border-neutral-800 img-zoom-container">
            <img src="<?= get_image_url('hero_banner_2.jpg', 'EDITORIAL LOOKBOOK', 800, 600) ?>" alt="Editorial" class="w-full h-full object-cover">
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
