<?php
// actions/cart_action.php

require_once __DIR__ . '/../config/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$db = Database::getConnection();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action === 'add') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = max(1, (int)($_POST['quantity'] ?? 1));
    $size = sanitize($_POST['size'] ?? 'Standard');
    $color = sanitize($_POST['color'] ?? 'Standard');

    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $item_key = $product_id . '-' . md5($size . '-' . $color);

        if (isset($_SESSION['cart'][$item_key])) {
            $_SESSION['cart'][$item_key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$item_key] = [
                'key' => $item_key,
                'product_id' => $product['id'],
                'title' => $product['title'],
                'price' => (float)$product['price'],
                'image' => $product['image'],
                'size' => $size,
                'color' => $color,
                'quantity' => $quantity
            ];
        }
        set_flash_message('success', 'Produk "' . $product['title'] . '" ditambahkan ke keranjang belanja.');
    } else {
        set_flash_message('error', 'Produk tidak ditemukan.');
    }

    $redirect = $_SERVER['HTTP_REFERER'] ?? base_url('cart.php');
    header('Location: ' . $redirect);
    exit();
}

if ($action === 'update') {
    $item_key = $_POST['item_key'] ?? '';
    $quantity = (int)($_POST['quantity'] ?? 1);

    if (isset($_SESSION['cart'][$item_key])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$item_key]);
            set_flash_message('info', 'Item dihapus dari keranjang.');
        } else {
            $_SESSION['cart'][$item_key]['quantity'] = $quantity;
            set_flash_message('success', 'Jumlah produk diperbarui.');
        }
    }

    header('Location: ' . base_url('cart.php'));
    exit();
}

if ($action === 'remove') {
    $item_key = $_GET['item_key'] ?? $_POST['item_key'] ?? '';
    if (isset($_SESSION['cart'][$item_key])) {
        unset($_SESSION['cart'][$item_key]);
        set_flash_message('info', 'Produk dihapus dari keranjang.');
    }
    header('Location: ' . base_url('cart.php'));
    exit();
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    set_flash_message('info', 'Keranjang belanja dikosongkan.');
    header('Location: ' . base_url('cart.php'));
    exit();
}

header('Location: ' . base_url('cart.php'));
exit();
