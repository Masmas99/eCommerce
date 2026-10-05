<?php
// admin/categories.php
$page_title = "Manajemen Kategori";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$categories = $db->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
?>

<div x-data="{ addCatModal: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-neutral-800 pb-4 mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-wider text-white">KATEGORI PRODUK</h1>
            <p class="text-xs text-neutral-400 mt-1 uppercase tracking-widest">Pengaturan Pengelompokan Katalog Produk</p>
        </div>
        <div>
            <button @click="addCatModal = true" class="bg-white text-black font-extrabold text-xs uppercase tracking-widest px-5 py-3 hover:bg-neutral-200 transition-colors">
                + TAMBAH KATEGORI
            </button>
        </div>
    </div>

    <!-- Categories Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($categories as $cat): ?>
            <div class="bg-neutral-900 border border-neutral-800 p-5 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-neutral-800 border border-neutral-700 overflow-hidden flex-shrink-0">
                        <img src="<?= get_image_url($cat['image'], $cat['category_name'], 200, 200) ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase text-white"><?= htmlspecialchars($cat['category_name']) ?></h3>
                        <span class="text-[10px] text-neutral-500 font-mono">Slug: <?= htmlspecialchars($cat['slug']) ?></span>
                    </div>
                </div>
                <div>
                    <a href="<?= base_url('actions/admin_action.php?action=delete_category&id=' . $cat['id']) ?>" onclick="return confirm('Hapus kategori ini?')" class="text-rose-400 hover:text-rose-300 text-xs font-mono">
                        [Hapus]
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MODAL: ADD CATEGORY -->
    <div x-show="addCatModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
        <div @click.away="addCatModal = false" class="bg-neutral-900 border border-neutral-700 w-full max-w-md p-6 space-y-6 text-xs text-white">
            <div class="flex justify-between items-center border-b border-neutral-800 pb-3">
                <h3 class="text-sm font-black uppercase tracking-widest">Tambah Kategori</h3>
                <button @click="addCatModal = false" class="text-neutral-400 font-bold text-xl hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('actions/admin_action.php') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_category">

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Nama Kategori *</label>
                    <input type="text" name="category_name" required placeholder="Contoh: Outerwear & Coats" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Foto Sampul Kategori</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-neutral-950 border border-neutral-800 p-2 text-white text-xs">
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" @click="addCatModal = false" class="px-5 py-3 border border-neutral-700 text-neutral-400 hover:text-white uppercase font-bold">Batal</button>
                    <button type="submit" class="bg-white text-black px-6 py-3 font-extrabold uppercase tracking-widest hover:bg-neutral-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
