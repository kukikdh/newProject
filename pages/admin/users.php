<?php
include('../../includes/auth_check.php');
requireLogin();
requireAdmin();
include('../../includes/header.php');
include('../../config/db.php');
?>

<div class="main-content">
    <div class="dashboard-wrapper">
        <div class="title">
            <h1>Manage Users</h1>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['created_at']) . "</td>";
                        echo "<td>";
                        echo "<a href='edit_user.php?id=" . htmlspecialchars($user['id']) . "'>Edit</a> | ";
                        echo "<a href='delete_user.php?id=" . htmlspecialchars($user['id']) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="/newProject/public/js/script.js"></script>
</body>
</html>