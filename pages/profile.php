<?php
include('../includes/header.php');
include('../config/db.php');

$user_id = $_SESSION['user_id'];
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Get current user data
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ../auth/login.php');
    exit;
}
?>

<link rel="stylesheet" href="../public/css/4-profile.css" />

<div class="main-content">
  <h2>Profile</h2>

  <?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- Update Profile Form -->
  <div class="card">
    <h3>Update Profile</h3>
    <form method="post" action="../actions/profile_update.php">
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

      <label for="name">Name:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

      <h4>Change Password (optional)</h4>
      <label for="current_password">Current Password:</label>
      <input type="password" id="current_password" name="current_password">

      <label for="new_password">New Password:</label>
      <input type="password" id="new_password" name="new_password">

      <label for="confirm_password">Confirm New Password:</label>
      <input type="password" id="confirm_password" name="confirm_password">

      <button type="submit" class="btn">Update Profile</button>
    </form>
  </div>

  <!-- Danger Zone -->
  <div class="card danger">
    <h3>Danger Zone</h3>
    <p>This action cannot be undone. All your data will be permanently deleted.</p>
    <form method="post" action="../actions/delete_account.php" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

      <label>
        <input type="checkbox" name="confirm_delete" value="1" required>
        I understand that this will permanently delete my account and all associated data
      </label>

      <label for="delete_password">Enter your password to confirm:</label>
      <input type="password" id="delete_password" name="delete_password" required>

      <button type="submit" class="btn delete-btn">Delete Account</button>
    </form>
  </div>
</div>