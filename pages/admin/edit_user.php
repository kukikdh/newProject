<?php
include('../../includes/auth_check.php');
requireLogin();
requireAdmin();
include('../../includes/header.php');
include('../../config/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if ($name && $email && in_array($role, ['student', 'admin'])) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
        header('Location: users.php');
        exit;
    }
}

$stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php');
    exit;
}
?>

<div class="main-content">
    <div class="dashboard-wrapper">
        <div class="title">
            <h1>Edit User</h1>
        </div>

        <div class="card">
            <form method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="student" <?php if ($user['role'] == 'student') echo 'selected'; ?>>Student</option>
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn primary-btn">Update</button>
                <a href="users.php" class="btn">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="/newProject/public/js/script.js"></script>
</body>
</html>