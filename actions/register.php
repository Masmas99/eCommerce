<?php
// actions/register.php - Forwarder / Fallback endpoint
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['action'] = 'register';
    require __DIR__ . '/auth_action.php';
    exit();
}

header('Location: ' . base_url('register.php'));
exit();
