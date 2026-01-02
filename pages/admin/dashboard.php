<?php
include('../../includes/auth_check.php');
requireLogin();
requireAdmin();
include('../../includes/header.php');
?>

<div class="main-content">
    <div class="dashboard-wrapper">
        <div class="title">
            <h1>Admin Dashboard</h1>
        </div>
        <div class="welcome">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> (Admin)!</p>
        </div>

        <div class="card">
            <h3>Admin Panel</h3>
            <ul>
                <li><a href="users.php">Manage Users</a></li>
                <li><a href="<?= BASE_URL ?>/pages/dashboard.php">Back to Dashboard</a></li>
                <li><a href="<?= BASE_URL ?>/auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<script src="/newProject/public/js/script.js"></script>
</body>
</html>