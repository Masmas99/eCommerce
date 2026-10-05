<?php
// admin/banners.php
$page_title = "Manajemen Banner Promo";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$banners = $db->query("SELECT * FROM banners ORDER BY id DESC")->fetchAll();
?>

<div x-data="{ addBannerModal: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-neutral-800 pb-4 mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-wider text-white">BANNER PROMO HERO</h1>
            <p class="text-xs text-neutral-400 mt-1 uppercase tracking-widest">Pengaturan Banner & Slider Promosi Halaman Depan</p>
        </div>
        <div>
            <button @click="addBannerModal = true" class="bg-white text-black font-extrabold text-xs uppercase tracking-widest px-5 py-3 hover:bg-neutral-200 transition-colors">
                + TAMBAH BANNER
            </button>
        </div>
    </div>

    <!-- Banners List -->
    <div class="space-y-4">
        <?php foreach ($banners as $b): ?>
            <div class="bg-neutral-900 border border-neutral-800 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center space-x-6">
                    <div class="w-32 h-20 bg-neutral-800 border border-neutral-700 overflow-hidden flex-shrink-0">
                        <img src="<?= get_image_url($b['image'], $b['title'], 400, 250) ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-mono text-emerald-400 uppercase font-bold">STATUS: <?= $b['is_active'] ? 'AKTIF' : 'NON-AKTIF' ?></span>
                        <h3 class="text-base font-black uppercase text-white"><?= htmlspecialchars($b['title']) ?></h3>
                        <p class="text-xs text-neutral-400 font-light"><?= htmlspecialchars($b['subtitle']) ?></p>
                        <p class="text-[10px] text-neutral-500 font-mono">Link: <?= htmlspecialchars($b['link']) ?></p>
                    </div>
                </div>

                <div>
                    <a href="<?= base_url('actions/admin_action.php?action=delete_banner&id=' . $b['id']) ?>" onclick="return confirm('Hapus banner promo ini?')" class="bg-rose-950 text-rose-300 border border-rose-800 px-4 py-2 text-xs font-mono font-bold uppercase hover:bg-rose-900">
                        [Hapus Banner]
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MODAL: ADD BANNER -->
    <div x-show="addBannerModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
        <div @click.away="addBannerModal = false" class="bg-neutral-900 border border-neutral-700 w-full max-w-lg p-6 space-y-6 text-xs text-white">
            <div class="flex justify-between items-center border-b border-neutral-800 pb-3">
                <h3 class="text-sm font-black uppercase tracking-widest">Tambah Banner Promo</h3>
                <button @click="addBannerModal = false" class="text-neutral-400 font-bold text-xl hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('actions/admin_action.php') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_banner">

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Judul Banner *</label>
                    <input type="text" name="title" required placeholder="Contoh: THE MONOCHROME ESSENTIALS" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Sub-Judul / Deskripsi Singkat</label>
                    <input type="text" name="subtitle" placeholder="Contoh: Autumn/Winter 2026 Collection" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Link Tombol CTA (URL / Halaman)</label>
                    <input type="text" name="link" value="products.php" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Gambar Banner Hero (Landscape JPG/PNG)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-neutral-950 border border-neutral-800 p-2 text-white text-xs">
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" @click="addBannerModal = false" class="px-5 py-3 border border-neutral-700 text-neutral-400 hover:text-white uppercase font-bold">Batal</button>
                    <button type="submit" class="bg-white text-black px-6 py-3 font-extrabold uppercase tracking-widest hover:bg-neutral-200">Simpan Banner</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
