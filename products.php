<?php
// products.php
$page_title = "Koleksi Produk";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

$category_slug = sanitize($_GET['category'] ?? '');
$search = sanitize($_GET['search'] ?? '');
$badge = sanitize($_GET['badge'] ?? '');
$sort = sanitize($_GET['sort'] ?? 'newest');

$where = ["1=1"];
$params = [];

if ($category_slug) {
    $where[] = "c.slug = ?";
    $params[] = $category_slug;
}

if ($search) {
    $where[] = "(p.title LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($badge) {
    $where[] = "p.badge = ?";
    $params[] = $badge;
}

$orderBy = "p.id DESC";
if ($sort === 'price_low') {
    $orderBy = "p.price ASC";
} elseif ($sort === 'price_high') {
    $orderBy = "p.price DESC";
}

$sql = "SELECT p.*, c.category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE " . implode(" AND ", $where) . " 
        ORDER BY " . $orderBy;

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch all categories for filter sidebar
$categories = $db->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Page Header & Breadcrumbs -->
    <div class="border-b border-black pb-6 mb-8 flex flex-col md:flex-row md:items-end justify-between">
        <div>
            <div class="text-xs uppercase font-bold text-gray-400 space-x-2 mb-2">
                <a href="<?= base_url() ?>" class="hover:text-black">Home</a>
                <span>/</span>
                <span class="text-black">Katalog Produk</span>
            </div>
            <h1 class="text-3xl font-black uppercase tracking-wider">
                <?= $category_slug ? 'KATEGORI: ' . strtoupper(str_replace('-', ' ', $category_slug)) : ($search ? 'HASIL CARI: "' . strtoupper($search) . '"' : 'SEMUA KOLEKSI') ?>
            </h1>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Menampilkan <?= count($products) ?> Produk Minimalist</p>
        </div>

        <!-- Sorting Selector -->
        <div class="mt-4 md:mt-0 flex items-center space-x-3 text-xs uppercase font-bold">
            <span class="text-gray-500">Urutkan:</span>
            <form method="GET" action="<?= base_url('products.php') ?>" class="inline-block">
                <?php if ($category_slug): ?><input type="hidden" name="category" value="<?= htmlspecialchars($category_slug) ?>"><?php endif; ?>
                <?php if ($search): ?><input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                <?php if ($badge): ?><input type="hidden" name="badge" value="<?= htmlspecialchars($badge) ?>"><?php endif; ?>
                <select name="sort" onchange="this.form.submit()" class="border border-black bg-white p-2 text-xs font-bold focus:outline-none">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Terbaru</option>
                    <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Harga: Rendah ke Tinggi</option>
                    <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Harga: Tinggi ke Rendah</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filter -->
        <div class="space-y-8">
            <!-- Categories Filter -->
            <div class="border border-black p-5 bg-white space-y-4">
                <h3 class="text-xs font-black uppercase tracking-widest border-b border-gray-200 pb-2">Filter Kategori</h3>
                <ul class="space-y-2 text-xs font-bold uppercase tracking-wider">
                    <li>
                        <a href="<?= base_url('products.php') ?>" class="<?= empty($category_slug) ? 'text-black font-extrabold underline' : 'text-gray-500 hover:text-black' ?>">
                            Semua Kategori
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?= base_url('products.php?category=' . urlencode($cat['slug'])) ?>" class="<?= $category_slug === $cat['slug'] ? 'text-black font-extrabold underline' : 'text-gray-500 hover:text-black' ?>">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Badges Filter -->
            <div class="border border-black p-5 bg-white space-y-4">
                <h3 class="text-xs font-black uppercase tracking-widest border-b border-gray-200 pb-2">Filter Koleksi</h3>
                <div class="flex flex-col space-y-2 text-xs font-bold uppercase">
                    <a href="<?= base_url('products.php?badge=Limited+Edition') ?>" class="p-2 border border-gray-200 hover:border-black hover:bg-black hover:text-white transition-colors">Limited Edition</a>
                    <a href="<?= base_url('products.php?badge=New+Arrival') ?>" class="p-2 border border-gray-200 hover:border-black hover:bg-black hover:text-white transition-colors">New Arrival</a>
                    <a href="<?= base_url('products.php?badge=Sale') ?>" class="p-2 border border-gray-200 hover:border-black hover:bg-black hover:text-white transition-colors">Sale</a>
                </div>
            </div>
        </div>

        <!-- Product Grid List -->
        <div class="lg:col-span-3">
            <?php if (empty($products)): ?>
                <div class="bg-white border border-black p-12 text-center space-y-4">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h3 class="text-lg font-bold uppercase tracking-wider">Produk Tidak Ditemukan</h3>
                    <p class="text-xs text-gray-500">Coba kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
                    <a href="<?= base_url('products.php') ?>" class="inline-block bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest">Reset Filter</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                    <?php foreach ($products as $prod): ?>
                        <div class="group flex flex-col justify-between bg-white border border-gray-200 card-lift reveal">
                            <div>
                                <div class="relative overflow-hidden aspect-[4/5] max-h-64 sm:max-h-80 bg-neutral-100 img-zoom-container">
                                    <a href="<?= base_url('product-detail.php?slug=' . urlencode($prod['slug'])) ?>">
                                        <img src="<?= get_image_url($prod['image'], $prod['title'], 600, 750) ?>" alt="<?= htmlspecialchars($prod['title']) ?>" class="w-full h-full object-cover">
                                    </a>
                                    <?php if (!empty($prod['badge'])): ?>
                                        <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-black text-white text-[9px] sm:text-[10px] font-black tracking-widest px-2 py-0.5 sm:px-3 sm:py-1 uppercase shadow-md">
                                            <?= htmlspecialchars($prod['badge']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
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
                            <div class="p-3 sm:p-4 pt-0">
                                <a href="<?= base_url('product-detail.php?slug=' . urlencode($prod['slug'])) ?>" class="block w-full text-center border border-black text-black py-2.5 sm:py-3 text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">
                                    DETAIL PRODUK
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
