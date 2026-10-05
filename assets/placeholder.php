<?php
// assets/placeholder.php - Dynamic High-Fashion Minimalist Image Generator

header("Content-Type: image/svg+xml");

$text = $_GET['text'] ?? 'FITRIII ESSENTIALS';
$bg = $_GET['bg'] ?? '18181b'; // dark zinc
$color = $_GET['color'] ?? 'f4f4f5'; // light zinc
$w = (int)($_GET['w'] ?? 800);
$h = (int)($_GET['h'] ?? 1000);

$text_clean = htmlspecialchars(strtoupper($text), ENT_QUOTES, 'UTF-8');
?>
<svg xmlns="http://www.w3.org/2000/svg" width="<?= $w ?>" height="<?= $h ?>" viewBox="0 0 <?= $w ?> <?= $h ?>">
    <rect width="100%" height="100%" fill="#<?= $bg ?>"/>
    
    <!-- Minimalist Grid / Geometry lines -->
    <rect x="40" y="40" width="<?= $w - 80 ?>" height="<?= $h - 80 ?>" stroke="#3f3f46" stroke-width="1" fill="none"/>
    <line x1="40" y1="<?= $h / 2 ?>" x2="<?= $w - 40 ?>" y2="<?= $h / 2 ?>" stroke="#27272a" stroke-width="1" stroke-dasharray="4,4"/>
    <line x1="<?= $w / 2 ?>" y1="40" x2="<?= $w / 2 ?>" y2="<?= $h - 40 ?>" stroke="#27272a" stroke-width="1" stroke-dasharray="4,4"/>
    
    <!-- Logo Monogram -->
    <text x="60" y="80" font-family="'Plus Jakarta Sans', sans-serif" font-size="14" font-weight="900" fill="#71717a" letter-spacing="4">PIDII STUDIO</text>
    <text x="<?= $w - 60 ?>" y="80" font-family="'Plus Jakarta Sans', sans-serif" font-size="12" font-weight="700" fill="#71717a" text-anchor="end" letter-spacing="2">AUTUMN / WINTER</text>
    
    <!-- Central Product Title -->
    <g transform="translate(<?= $w / 2 ?>, <?= $h / 2 ?>)">
        <!-- Circle Accent -->
        <circle r="90" fill="none" stroke="#3f3f46" stroke-width="1"/>
        <text x="0" y="10" font-family="'Playfair Display', serif" font-size="32" font-weight="700" fill="#<?= $color ?>" text-anchor="middle" letter-spacing="2"><?= $text_clean ?></text>
    </g>
    
    <!-- Footer watermark -->
    <text x="<?= $w / 2 ?>" y="<?= $h - 60 ?>" font-family="sans-serif" font-size="11" font-weight="600" fill="#52525b" text-anchor="middle" letter-spacing="3">HIGH-FASHION MINIMALIST DESIGN</text>
</svg>
