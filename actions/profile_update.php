<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid request';
    } else {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($name)) {
            $error = 'Name is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } else {
            // Check email uniqueness if changed
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user_id]);
            if ($stmt->fetch()) {
                $error = 'Email already in use';
            }

            if (empty($error)) {
                // Handle password change
                $update_password = false;
                if (!empty($new_password)) {
                    if (empty($current_password)) {
                        $error = 'Current password required to change password';
                    } elseif (strlen($new_password) < 6) {
                        $error = 'New password must be at least 6 characters';
                    } elseif ($new_password !== $confirm_password) {
                        $error = 'New passwords do not match';
                    } else {
                        // Verify current password
                        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                        $stmt->execute([$user_id]);
                        $stored_hash = $stmt->fetch()['password'];
                        if (!password_verify($current_password, $stored_hash)) {
                            $error = 'Current password is incorrect';
                        } else {
                            $update_password = true;
                        }
                    }
                }

                if (empty($error)) {
                    // Update user
                    if ($update_password) {
                        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                        $stmt->execute([$name, $email, $hashed, $user_id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                        $stmt->execute([$name, $email, $user_id]);
                    }

                    // Update session
                    $_SESSION['name'] = $name;
                    $message = 'Profile updated successfully';
                }
            }
        }
    }
}

// Redirect back with message
$query = $message ? 'message=' . urlencode($message) : ($error ? 'error=' . urlencode($error) : '');
header('Location: ../pages/profile.php?' . $query);
exit;
?>