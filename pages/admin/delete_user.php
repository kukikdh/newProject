<?php
include('../../includes/auth_check.php');
requireLogin();
requireAdmin();
include('../../config/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];

if ($id == $_SESSION['user_id']) {
    // Prevent deleting self
    header('Location: users.php?error=self');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header('Location: users.php');
exit;
?>