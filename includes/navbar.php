<div class="navbar">
  <div class="nav-right">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <span><a href="<?= BASE_URL ?>/pages/admin/dashboard.php">Admin Panel</a></span>
    <?php endif; ?>
    <span><a href="<?= BASE_URL ?>/pages/profile.php">Profile</a></span>
    <span><a href="<?= BASE_URL ?>/auth/logout.php">Sign out</a></span>
  </div>
</div>
