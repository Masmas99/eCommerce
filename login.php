<?php
// login.php
$page_title = "Login Masuk Akun";
require_once __DIR__ . '/includes/header.php';

if (is_logged_in()) {
    header('Location: ' . base_url('index.php'));
    exit();
}
?>

<div class="max-w-md mx-auto px-4 py-20">
    <div class="bg-white border-2 border-black p-8 space-y-6 shadow-none">
        
        <div class="text-center space-y-2 border-b border-black pb-4">
            <span class="text-2xl font-black tracking-[0.25em] uppercase border-y-2 border-black py-1 px-3 inline-block">PIDII</span>
            <h1 class="text-lg font-black uppercase tracking-wider pt-2">MASUK KE AKUN ANDA</h1>
            <p class="text-xs text-gray-500">Akses koleksi eksklusif dan riwayat pesanan Anda.</p>
        </div>

        <!-- Default Credentials Alert Callout -->
        <div class="bg-neutral-100 border border-black p-4 text-xs space-y-1 font-mono">
            <p class="font-bold font-sans text-[11px] uppercase">✦ Akun Demo Siap Pakai:</p>
            <p><strong>Admin:</strong> admin@example.com / adminpassword</p>
            <p><strong>Customer:</strong> customer@example.com / customerpassword</p>
        </div>

        <form action="<?= base_url('actions/auth_action.php') ?>" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="login">

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Alamat Email</label>
                <input type="email" name="email" required placeholder="name@example.com" class="w-full border border-black p-3 text-xs focus:outline-none font-medium">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-wider block">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full border border-black p-3 text-xs focus:outline-none font-medium">
            </div>

            <button type="submit" class="w-full bg-black text-white py-4 font-black text-xs uppercase tracking-[0.2em] hover:bg-neutral-800 transition-colors">
                MASUK SEKARANG &rarr;
            </button>
        </form>

        <div class="text-center text-xs border-t border-gray-200 pt-4">
            <span class="text-gray-500">Belum memiliki akun?</span>
            <a href="<?= base_url('register.php') ?>" class="font-bold underline text-black hover:text-gray-600">Daftar Akun Baru</a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
