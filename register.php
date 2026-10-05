<?php
// register.php
$page_title = "Pendaftaran Akun Baru";
require_once __DIR__ . '/includes/header.php';

if (is_logged_in()) {
    header('Location: ' . base_url('index.php'));
    exit();
}
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white border-2 border-black p-8 space-y-6 shadow-none">
        
        <div class="text-center space-y-2 border-b border-black pb-4">
            <span class="text-2xl font-black tracking-[0.25em] uppercase border-y-2 border-black py-1 px-3 inline-block">PIDII</span>
            <h1 class="text-lg font-black uppercase tracking-wider pt-2">REGISTRASI AKUN BARU</h1>
            <p class="text-xs text-gray-500">Bergabunglah untuk pengalaman belanja eksklusif.</p>
        </div>

        <form action="<?= base_url('actions/auth_action.php') ?>" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="register">

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Nama Anda" class="w-full border border-black p-3 text-xs focus:outline-none font-medium">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Alamat Email *</label>
                <input type="email" name="email" required placeholder="email@domain.com" class="w-full border border-black p-3 text-xs focus:outline-none font-medium">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Password (Min. 6 karakter) *</label>
                <input type="password" name="password" required minlength="6" placeholder="••••••••" class="w-full border border-black p-3 text-xs focus:outline-none font-medium">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Nomor Telepon / WhatsApp</label>
                <input type="tel" name="phone" placeholder="081234567890" class="w-full border border-black p-3 text-xs focus:outline-none font-medium">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Alamat Pengiriman Utamanya</label>
                <textarea name="address" rows="2" placeholder="Jalan, Kota, Kode Pos" class="w-full border border-black p-3 text-xs focus:outline-none font-medium"></textarea>
            </div>

            <button type="submit" class="w-full bg-black text-white py-4 font-black text-xs uppercase tracking-[0.2em] hover:bg-neutral-800 transition-colors">
                DAFTAR AKUN SEKARANG &rarr;
            </button>
        </form>

        <div class="text-center text-xs border-t border-gray-200 pt-4">
            <span class="text-gray-500">Sudah memiliki akun?</span>
            <a href="<?= base_url('login.php') ?>" class="font-bold underline text-black hover:text-gray-600">Masuk di Sini</a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
