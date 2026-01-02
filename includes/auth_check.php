<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }
}

function requireAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ../pages/dashboard.php');
        exit;
    }
}
?>