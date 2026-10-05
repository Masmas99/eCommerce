<?php
// admin/products.php
$page_title = "Manajemen Produk";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Fetch products & categories
$categories = $db->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();
$products = $db->query("SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
?>

<div x-data="{ addModal: false, editModal: false, editItem: {} }">
    
    <!-- Top Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-neutral-800 pb-4 mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-wider text-white">MANAJEMEN PRODUK</h1>
            <p class="text-xs text-neutral-400 mt-1 uppercase tracking-widest">Kelola Katalog, Harga, Stok, & Varian Produk</p>
        </div>
        <div>
            <button @click="addModal = true" class="bg-white text-black font-extrabold text-xs uppercase tracking-widest px-5 py-3 hover:bg-neutral-200 transition-colors flex items-center space-x-2">
                <span>+ TAMBAH PRODUK BARU</span>
            </button>
        </div>
    </div>

    <!-- Products Data Table -->
    <div class="bg-neutral-900 border border-neutral-800 p-6 space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs uppercase font-mono border-collapse">
                <thead>
                    <tr class="border-b border-neutral-800 text-neutral-400 font-bold">
                        <th class="py-3 px-4">Foto</th>
                        <th class="py-3 px-4">Judul Produk</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4">Badge</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-neutral-200">
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-500 font-sans">Belum ada produk terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $prod): ?>
                            <tr class="hover:bg-neutral-800/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="w-12 h-14 bg-neutral-800 border border-neutral-700 overflow-hidden">
                                        <img src="<?= get_image_url($prod['image'], $prod['title'], 100, 120) ?>" class="w-full h-full object-cover">
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-bold text-white font-sans">
                                    <?= htmlspecialchars($prod['title']) ?>
                                    <span class="block text-[10px] text-neutral-500 font-mono font-normal">Sizes: <?= htmlspecialchars($prod['sizes']) ?></span>
                                </td>
                                <td class="py-3 px-4 text-neutral-400 font-sans"><?= htmlspecialchars($prod['category_name']) ?></td>
                                <td class="py-3 px-4 font-bold text-white"><?= format_rupiah($prod['price']) ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-[10px] font-bold border <?= $prod['stock'] > 5 ? 'border-emerald-800 bg-emerald-950 text-emerald-300' : 'border-rose-800 bg-rose-950 text-rose-300' ?>">
                                        <?= $prod['stock'] ?> unit
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-[10px] text-neutral-400 font-bold"><?= htmlspecialchars($prod['badge'] ?: '-') ?></span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <button @click="editItem = <?= htmlspecialchars(json_encode($prod), ENT_QUOTES, 'UTF-8') ?>; editModal = true" class="bg-neutral-800 text-white border border-neutral-700 px-3 py-1 text-[10px] font-bold uppercase hover:bg-neutral-700">
                                        Edit
                                    </button>
                                    <a href="<?= base_url('actions/admin_action.php?action=delete_product&id=' . $prod['id']) ?>" onclick="return confirm('Hapus produk ini secara permanen?')" class="bg-rose-950 text-rose-300 border border-rose-800 px-3 py-1 text-[10px] font-bold uppercase hover:bg-rose-900">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: ADD PRODUCT -->
    <div x-show="addModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
        <div @click.away="addModal = false" class="bg-neutral-900 border border-neutral-700 w-full max-w-2xl p-6 space-y-6 text-xs text-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-neutral-800 pb-3">
                <h3 class="text-sm font-black uppercase tracking-widest">Tambah Produk Baru</h3>
                <button @click="addModal = false" class="text-neutral-400 font-bold text-xl hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('actions/admin_action.php') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_product">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Judul Produk *</label>
                        <input type="text" name="title" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Kategori *</label>
                        <select name="category_id" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Harga (Rp) *</label>
                        <input type="number" step="1000" name="price" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Stok *</label>
                        <input type="number" name="stock" value="10" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Badge Tag</label>
                        <select name="badge" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                            <option value="">Tanpa Badge</option>
                            <option value="Limited Edition">Limited Edition</option>
                            <option value="New Arrival">New Arrival</option>
                            <option value="Sale">Sale</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Pilihan Ukuran (Pisahkan Koma)</label>
                        <input type="text" name="sizes" value="S, M, L, XL" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Pilihan Warna (Pisahkan Koma)</label>
                        <input type="text" name="colors" value="Deep Black, Off-White" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Deskripsi Produk</label>
                    <textarea name="description" rows="3" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Foto Produk (JPG / PNG)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-neutral-950 border border-neutral-800 p-2 text-white text-xs">
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" @click="addModal = false" class="px-5 py-3 border border-neutral-700 text-neutral-400 hover:text-white uppercase font-bold">Batal</button>
                    <button type="submit" class="bg-white text-black px-6 py-3 font-extrabold uppercase tracking-widest hover:bg-neutral-200">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT PRODUCT -->
    <div x-show="editModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
        <div @click.away="editModal = false" class="bg-neutral-900 border border-neutral-700 w-full max-w-2xl p-6 space-y-6 text-xs text-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-neutral-800 pb-3">
                <h3 class="text-sm font-black uppercase tracking-widest">Edit Produk</h3>
                <button @click="editModal = false" class="text-neutral-400 font-bold text-xl hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('actions/admin_action.php') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="edit_product">
                <input type="hidden" name="id" :value="editItem.id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Judul Produk *</label>
                        <input type="text" name="title" :value="editItem.title" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Kategori *</label>
                        <select name="category_id" :value="editItem.category_id" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Harga (Rp) *</label>
                        <input type="number" step="1000" name="price" :value="editItem.price" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Stok *</label>
                        <input type="number" name="stock" :value="editItem.stock" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Badge Tag</label>
                        <select name="badge" :value="editItem.badge" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                            <option value="">Tanpa Badge</option>
                            <option value="Limited Edition">Limited Edition</option>
                            <option value="New Arrival">New Arrival</option>
                            <option value="Sale">Sale</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Ukuran</label>
                        <input type="text" name="sizes" :value="editItem.sizes" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold uppercase tracking-wider block">Warna</label>
                        <input type="text" name="colors" :value="editItem.colors" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Deskripsi Produk</label>
                    <textarea name="description" rows="3" x-text="editItem.description" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Ganti Foto (Kosongkan jika tidak diubah)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-neutral-950 border border-neutral-800 p-2 text-white text-xs">
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" @click="editModal = false" class="px-5 py-3 border border-neutral-700 text-neutral-400 hover:text-white uppercase font-bold">Batal</button>
                    <button type="submit" class="bg-white text-black px-6 py-3 font-extrabold uppercase tracking-widest hover:bg-neutral-200">Update Produk</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
