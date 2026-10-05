<?php
// actions/auth_action.php

require_once __DIR__ . '/../config/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$db = Database::getConnection();

if ($action === 'register') {
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');

    if (!$name || !$email || strlen($password) < 6) {
        set_flash_message('error', 'Lengkapi data pendaftaran. Password minimal 6 karakter.');
        header('Location: ' . base_url('register.php'));
        exit();
    }

    // Check existing email
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        set_flash_message('error', 'Email sudah terdaftar. Silakan login.');
        header('Location: ' . base_url('register.php'));
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (name, email, password, role, phone, address) VALUES (?, ?, ?, 'customer', ?, ?)");
    $stmt->execute([$name, $email, $hashed_password, $phone, $address]);

    $user_id = $db->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'customer';

    set_flash_message('success', 'Pendaftaran berhasil. Selamat datang di PIDII!');
    header('Location: ' . base_url('index.php'));
    exit();
}

if ($action === 'login') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        set_flash_message('error', 'Email dan password harus diisi.');
        header('Location: ' . base_url('login.php'));
        exit();
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        set_flash_message('success', 'Selamat datang kembali, ' . $user['name'] . '!');

        if ($user['role'] === 'admin') {
            header('Location: ' . base_url('admin/index.php'));
        } else {
            header('Location: ' . base_url('index.php'));
        }
        exit();
    } else {
        set_flash_message('error', 'Email atau password salah.');
        header('Location: ' . base_url('login.php'));
        exit();
    }
}

if ($action === 'logout') {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_role']);
    session_destroy();

    header('Location: ' . base_url('index.php'));
    exit();
}

header('Location: ' . base_url());
exit();
