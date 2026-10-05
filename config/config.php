<?php
// config/config.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

// Base URL detection
define('APP_NAME', 'PIDII | HIGH-FASHION MINIMALIST');
define('CURRENCY_SYMBOL', 'Rp ');

function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    
    // Normalize path to root of project if nested in admin or actions
    if (strpos($scriptDir, '/admin') !== false) {
        $scriptDir = str_replace('/admin', '', $scriptDir);
    }
    if (strpos($scriptDir, '/actions') !== false) {
        $scriptDir = str_replace('/actions', '', $scriptDir);
    }
    
    $base = rtrim($protocol . "://" . $host . rtrim($scriptDir, '/\\'), '/\\');
    return $base . '/' . ltrim($path, '/');
}


function format_rupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'error', 'info'
        'message' => $message
    ];
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        set_flash_message('error', 'Silakan login terlebih dahulu untuk melanjutkan.');
        header('Location: ' . base_url('login.php'));
        exit();
    }
}

function require_admin() {
    if (!is_admin()) {
        set_flash_message('error', 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        header('Location: ' . base_url('login.php'));
        exit();
    }
}

function get_cart_count() {
    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'];
    }
    return $total;
}

function get_cart_total() {
    $cart = $_SESSION['cart'] ?? [];
    $total = 0.0;
    foreach ($cart as $item) {
        $total += ($item['price'] * $item['quantity']);
    }
    return $total;
}

function get_image_url($filename, $title = 'PIDII', $w = 800, $h = 1000) {
    if (!empty($filename) && file_exists(__DIR__ . '/../assets/uploads/' . $filename)) {
        return base_url('assets/uploads/' . $filename);
    }
    return base_url('assets/placeholder.php?text=' . urlencode($title) . '&w=' . $w . '&h=' . $h);
}

