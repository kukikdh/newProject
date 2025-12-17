<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Student Productivity Tracker</title>
  <link rel="stylesheet" href="../public/css/base.css" />
  <link rel="stylesheet" href="../public/css/components.css" />
</head>
<body>
<div class="auth-content">
  <h2>Sign Up</h2>
  <form method="post">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
    <button type="submit">Sign Up</button>
  </form>
  <p><a href="login.php">Already have an account? Login</a></p>
  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../config/db.php');
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
      echo "<p style='color:red;'>Passwords do not match.</p>";
      exit;
    }
    if (strlen($password) < 6) {
      echo "<p style='color:red;'>Password must be at least 6 characters.</p>";
      exit;
    }

    $userId = 'u_' . bin2hex(random_bytes(7)); // Generates ~20 char string
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)");
    try {
      $stmt->execute([$userId, $name, $email, $hashed]);
      header('Location: login.php');
    } catch (PDOException $e) {
      echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
  }
  ?>
</div>
</body>
</html>