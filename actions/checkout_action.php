<?php
// actions/checkout_action.php

require_once __DIR__ . '/../config/config.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    set_flash_message('error', 'Keranjang belanja Anda kosong.');
    header('Location: ' . base_url('products.php'));
    exit();
}

$name = sanitize($_POST['customer_name'] ?? '');
$email = filter_var($_POST['customer_email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = sanitize($_POST['customer_phone'] ?? '');
$address = sanitize($_POST['shipping_address'] ?? '');
$courier = sanitize($_POST['courier'] ?? 'JNE Express');
$shipping_cost = (float)($_POST['shipping_cost'] ?? 25000);
$payment_method = sanitize($_POST['payment_method'] ?? 'Bank Transfer (BCA)');

if (!$name || !$email || !$phone || !$address) {
    set_flash_message('error', 'Mohon lengkapi seluruh formulir data pengiriman.');
    header('Location: ' . base_url('checkout.php'));
    exit();
}

$cart_subtotal = get_cart_total();
$grand_total = $cart_subtotal + $shipping_cost;

// Generate Order Number
$order_number = 'PDI-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
$user_id = $_SESSION['user_id'] ?? null;

$db = Database::getConnection();

try {
    $db->beginTransaction();

    $stmt = $db->prepare("INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, shipping_address, courier, shipping_cost, grand_total, status, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?)");
    $stmt->execute([$order_number, $user_id, $name, $email, $phone, $address, $courier, $shipping_cost, $grand_total, $payment_method]);
    
    $order_id = $db->lastInsertId();

    $item_stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_title, size, color, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stock_stmt = $db->prepare("UPDATE products SET stock = GREATEST(0, stock - ?) WHERE id = ?");

    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $item_stmt->execute([
            $order_id,
            $item['product_id'],
            $item['title'],
            $item['size'],
            $item['color'],
            $item['price'],
            $item['quantity'],
            $subtotal
        ]);

        // Reduce product stock
        $stock_stmt->execute([$item['quantity'], $item['product_id']]);
    }

    $db->commit();

    // Clear cart after successful checkout
    unset($_SESSION['cart']);

    set_flash_message('success', 'Pesanan Anda berhasil dibuat!');
    header('Location: ' . base_url('order-success.php?order_number=' . urlencode($order_number)));
    exit();

} catch (Exception $e) {
    $db->rollBack();
    set_flash_message('error', 'Terjadi kesalahan sistem saat memproses pesanan. Silakan coba lagi.');
    header('Location: ' . base_url('checkout.php'));
    exit();
}
