<?php
// admin/includes/header.php
require_once __DIR__ . '/../../config/config.php';
require_admin();

$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Admin PIDII' : 'Admin Control Panel | PIDII' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            bg: '#0F0F0F',
                            card: '#171717',
                            border: '#262626',
                            text: '#F5F5F5'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F0F0F;
            color: #F5F5F5;
        }
    </style>
</head>
<body class="min-h-screen flex bg-[#0F0F0F] text-neutral-100">

    <!-- Sidebar Component -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- Right Content Shell -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar Navbar -->
        <header class="bg-neutral-900 border-b border-neutral-800 h-16 flex items-center justify-between px-6">
            <div class="flex items-center space-x-4">
                <span class="text-xs font-mono font-bold text-neutral-400 uppercase tracking-widest">
                    SYSTEM TIME: <?= date('Y-m-d H:i') ?>
                </span>
            </div>

            <div class="flex items-center space-x-6 text-xs uppercase font-bold">
                <a href="<?= base_url() ?>" target="_blank" class="text-neutral-400 hover:text-white flex items-center space-x-1">
                    <span>Lihat Toko Front-End</span>
                    <span>&nearr;</span>
                </a>
                <div class="flex items-center space-x-3 border-l border-neutral-800 pl-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-white"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                    <a href="<?= base_url('logout.php') ?>" class="text-rose-400 hover:text-rose-300">Logout</a>
                </div>
            </div>
        </header>

        <!-- Flash Alert -->
        <?php if ($flash): ?>
            <div class="p-4 mx-6 mt-4 font-medium text-xs flex items-center justify-between <?= $flash['type'] === 'success' ? 'bg-emerald-950 text-emerald-200 border border-emerald-800' : 'bg-rose-950 text-rose-200 border border-rose-800' ?>">
                <span><?= htmlspecialchars($flash['message']) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-4 font-bold text-base">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Main Page Body -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">
