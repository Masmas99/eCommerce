<?php
// admin/orders.php
$page_title = "Manajemen Pesanan";
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Fetch orders with order items
$orders = $db->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll();
?>

<div x-data="{ statusModal: false, selectedOrder: {} }">
    
    <!-- Top Header -->
    <div class="border-b border-neutral-800 pb-4 mb-6">
        <h1 class="text-2xl font-black uppercase tracking-wider text-white">MANAJEMEN PESANAN MASUK</h1>
        <p class="text-xs text-neutral-400 mt-1 uppercase tracking-widest">Update Status Pembayaran, Pengiriman, & Input Nomor Resi</p>
    </div>

    <!-- Orders Table -->
    <div class="bg-neutral-900 border border-neutral-800 p-6 space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs uppercase font-mono border-collapse">
                <thead>
                    <tr class="border-b border-neutral-800 text-neutral-400 font-bold">
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Kurir</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">No. Resi</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800 text-neutral-200">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-neutral-500 font-sans">Belum ada pesanan terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $ord): ?>
                            <tr class="hover:bg-neutral-800/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-white"><?= htmlspecialchars($ord['order_number']) ?></td>
                                <td class="py-3 px-4 font-sans font-medium text-neutral-300">
                                    <?= htmlspecialchars($ord['customer_name']) ?>
                                    <span class="block text-[10px] text-neutral-500 font-mono"><?= htmlspecialchars($ord['customer_phone']) ?></span>
                                </td>
                                <td class="py-3 px-4 text-neutral-400 font-sans"><?= htmlspecialchars($ord['courier']) ?></td>
                                <td class="py-3 px-4 font-bold text-white"><?= format_rupiah($ord['grand_total']) ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold border border-neutral-700 bg-neutral-800 text-white">
                                        <?= htmlspecialchars($ord['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-mono text-neutral-300">
                                    <?= htmlspecialchars($ord['tracking_number'] ?: '-') ?>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button @click="selectedOrder = <?= htmlspecialchars(json_encode($ord), ENT_QUOTES, 'UTF-8') ?>; statusModal = true" class="bg-white text-black px-3 py-1 text-[10px] font-bold uppercase hover:bg-neutral-200">
                                        Update Status / Resi
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: UPDATE STATUS & RESI -->
    <div x-show="statusModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4">
        <div @click.away="statusModal = false" class="bg-neutral-900 border border-neutral-700 w-full max-w-lg p-6 space-y-6 text-xs text-white">
            <div class="flex justify-between items-center border-b border-neutral-800 pb-3">
                <h3 class="text-sm font-black uppercase tracking-widest">Update Order <span x-text="selectedOrder.order_number" class="text-emerald-400"></span></h3>
                <button @click="statusModal = false" class="text-neutral-400 font-bold text-xl hover:text-white">&times;</button>
            </div>

            <form action="<?= base_url('actions/admin_action.php') ?>" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_order_status">
                <input type="hidden" name="order_id" :value="selectedOrder.id">

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Status Pesanan *</label>
                    <select name="status" :value="selectedOrder.status" required class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white focus:outline-none focus:border-white">
                        <option value="Pending">Pending (Menunggu Pembayaran)</option>
                        <option value="Paid">Paid (Pembayaran Diterima / Diproses)</option>
                        <option value="Shipped">Shipped (Dalam Pengiriman)</option>
                        <option value="Completed">Completed (Pesanan Selesai)</option>
                        <option value="Cancelled">Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="font-bold uppercase tracking-wider block">Nomor Resi Pengiriman (Nomor Airwaybill / Tracking)</label>
                    <input type="text" name="tracking_number" :value="selectedOrder.tracking_number" placeholder="Contoh: JNE882019203" class="w-full bg-neutral-950 border border-neutral-800 p-3 text-white font-mono focus:outline-none focus:border-white">
                </div>

                <div class="bg-neutral-950 border border-neutral-800 p-4 space-y-1 font-mono text-[11px] text-neutral-400">
                    <p class="font-bold text-white font-sans uppercase">Alamat Tujuan Pengiriman:</p>
                    <p x-text="selectedOrder.shipping_address"></p>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" @click="statusModal = false" class="px-5 py-3 border border-neutral-700 text-neutral-400 hover:text-white uppercase font-bold">Batal</button>
                    <button type="submit" class="bg-white text-black px-6 py-3 font-extrabold uppercase tracking-widest hover:bg-neutral-200">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
