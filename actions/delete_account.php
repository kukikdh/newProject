<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid request';
    } elseif (empty($_POST['confirm_delete'])) {
        $error = 'Please confirm account deletion';
    } else {
        $password = $_POST['delete_password'];
        if (empty($password)) {
            $error = 'Password required for account deletion';
        } else {
            // Verify password
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $stored_hash = $stmt->fetch()['password'];
            if (!password_verify($password, $stored_hash)) {
                $error = 'Incorrect password';
            } else {
                // Delete account and related data
                try {
                    $pdo->beginTransaction();

                    // Delete timer sessions
                    $stmt = $pdo->prepare("DELETE FROM timer_sessions WHERE user_id = ?");
                    $stmt->execute([$user_id]);

                    // Delete tasks
                    $stmt = $pdo->prepare("DELETE FROM tasks WHERE user_id = ?");
                    $stmt->execute([$user_id]);

                    // Delete user
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);

                    $pdo->commit();

                    // Destroy session and redirect
                    session_destroy();
                    header('Location: ../auth/signup.php?message=Account deleted successfully');
                    exit;
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = 'Failed to delete account: ' . $e->getMessage();
                }
            }
        }
    }
}

// If error, redirect back
if ($error) {
    header('Location: ../pages/profile.php?error=' . urlencode($error));
} else {
    header('Location: ../pages/profile.php');
}
exit;
?>