<?php
// actions/admin_action.php

require_once __DIR__ . '/../config/config.php';
require_admin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$db = Database::getConnection();

// File Upload Helper
function handle_file_upload($fileKey) {
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
        $fileName = $_FILES[$fileKey]['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '/../assets/uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                return $newFileName;
            }
        }
    }
    return null;
}

// ----------------------------------------------------
// PRODUCT CRUD
// ----------------------------------------------------
if ($action === 'add_product') {
    $category_id = (int)$_POST['category_id'];
    $title = sanitize($_POST['title']);
    $slug = sanitize(strtolower(str_replace(' ', '-', $title)) . '-' . rand(100, 999));
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $badge = sanitize($_POST['badge'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $sizes = sanitize($_POST['sizes'] ?? 'S, M, L, XL');
    $colors = sanitize($_POST['colors'] ?? 'Black, Off-White');

    $image = handle_file_upload('image');

    $stmt = $db->prepare("INSERT INTO products (category_id, title, slug, price, stock, description, image, badge, sizes, colors) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $title, $slug, $price, $stock, $description, $image, $badge, $sizes, $colors]);

    set_flash_message('success', 'Produk baru "' . $title . '" berhasil ditambahkan.');
    header('Location: ' . base_url('admin/products.php'));
    exit();
}

if ($action === 'edit_product') {
    $id = (int)$_POST['id'];
    $category_id = (int)$_POST['category_id'];
    $title = sanitize($_POST['title']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $badge = sanitize($_POST['badge'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $sizes = sanitize($_POST['sizes'] ?? 'S, M, L, XL');
    $colors = sanitize($_POST['colors'] ?? 'Black, Off-White');

    $image = handle_file_upload('image');

    if ($image) {
        $stmt = $db->prepare("UPDATE products SET category_id = ?, title = ?, price = ?, stock = ?, description = ?, image = ?, badge = ?, sizes = ?, colors = ? WHERE id = ?");
        $stmt->execute([$category_id, $title, $price, $stock, $description, $image, $badge, $sizes, $colors, $id]);
    } else {
        $stmt = $db->prepare("UPDATE products SET category_id = ?, title = ?, price = ?, stock = ?, description = ?, badge = ?, sizes = ?, colors = ? WHERE id = ?");
        $stmt->execute([$category_id, $title, $price, $stock, $description, $badge, $sizes, $colors, $id]);
    }

    set_flash_message('success', 'Data produk berhasil diperbarui.');
    header('Location: ' . base_url('admin/products.php'));
    exit();
}

if ($action === 'delete_product') {
    $id = (int)($_GET['id'] ?? $_POST['id']);
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    set_flash_message('info', 'Produk berhasil dihapus.');
    header('Location: ' . base_url('admin/products.php'));
    exit();
}

// ----------------------------------------------------
// ORDER MANAGEMENT
// ----------------------------------------------------
if ($action === 'update_order_status') {
    $order_id = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    $tracking_number = sanitize($_POST['tracking_number'] ?? '');

    $stmt = $db->prepare("UPDATE orders SET status = ?, tracking_number = ? WHERE id = ?");
    $stmt->execute([$status, $tracking_number, $order_id]);

    set_flash_message('success', 'Status pesanan dan nomor resi berhasil diperbarui.');
    header('Location: ' . base_url('admin/orders.php'));
    exit();
}

// ----------------------------------------------------
// CATEGORY CRUD
// ----------------------------------------------------
if ($action === 'add_category') {
    $name = sanitize($_POST['category_name']);
    $slug = sanitize(strtolower(str_replace(' ', '-', $name)));
    $image = handle_file_upload('image');

    $stmt = $db->prepare("INSERT INTO categories (category_name, slug, image) VALUES (?, ?, ?)");
    $stmt->execute([$name, $slug, $image]);

    set_flash_message('success', 'Kategori baru ditambahkan.');
    header('Location: ' . base_url('admin/categories.php'));
    exit();
}

if ($action === 'delete_category') {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);

    set_flash_message('info', 'Kategori berhasil dihapus.');
    header('Location: ' . base_url('admin/categories.php'));
    exit();
}

// ----------------------------------------------------
// BANNER CRUD
// ----------------------------------------------------
if ($action === 'add_banner') {
    $title = sanitize($_POST['title']);
    $subtitle = sanitize($_POST['subtitle']);
    $link = sanitize($_POST['link'] ?? 'products.php');
    $image = handle_file_upload('image');

    $stmt = $db->prepare("INSERT INTO banners (title, subtitle, link, image, is_active) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$title, $subtitle, $link, $image]);

    set_flash_message('success', 'Banner promo baru ditambahkan.');
    header('Location: ' . base_url('admin/banners.php'));
    exit();
}

if ($action === 'delete_banner') {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("DELETE FROM banners WHERE id = ?");
    $stmt->execute([$id]);

    set_flash_message('info', 'Banner berhasil dihapus.');
    header('Location: ' . base_url('admin/banners.php'));
    exit();
}

header('Location: ' . base_url('admin/index.php'));
exit();
