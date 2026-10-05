<?php
// actions/login.php - Forwarder / Fallback endpoint
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['action'] = 'login';
    require __DIR__ . '/auth_action.php';
    exit();
}

header('Location: ' . base_url('login.php'));
exit();
