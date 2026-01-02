<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Student Productivity Tracker</title>
  <link rel="stylesheet" href="../public/css/0-variables.css" />
  <link rel="stylesheet" href="../public/css/1-reset.css" />
  <link rel="stylesheet" href="../public/css/2-layout.css" />
  <link rel="stylesheet" href="../public/css/3-components.css" />
</head>
<body>
<div class="auth-content">
  <h2>Sign Up</h2>
  <form method="post">
    <div class="form-group">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your full name" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Create a password" required>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
    </div>
    <button type="submit" class="btn primary-btn">Sign Up</button>
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
      $_SESSION['success'] = 'Account created successfully! Please login.';
      header('Location: login.php');
    } catch (PDOException $e) {
      echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
  }
  ?>
</div>
</body>
</html>