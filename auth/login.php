<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Student Productivity Tracker</title>
  <link rel="stylesheet" href="../public/css/0-variables.css" />
  <link rel="stylesheet" href="../public/css/1-reset.css" />
  <link rel="stylesheet" href="../public/css/2-layout.css" />
  <link rel="stylesheet" href="../public/css/3-components.css" />
</head>
<body>
<div class="auth-content">
  <h2>Login</h2>
  <form method="post">
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
    </div>
    <button type="submit" class="btn primary-btn">Login</button>
  </form>
  <p><a href="signup.php">Don't have an account? Sign Up</a></p>
  <?php
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../config/db.php');
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['name'] = $user['name'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['success'] = 'Login successful!';
      header('Location: ../pages/dashboard.php');
    } else {
      echo "<p style='color:red;'>Invalid email or password.</p>";
    }
  }
  if(isset($_SESSION['success'])) {
    echo "<script>alert('".$_SESSION['success']."');</script>";
    unset($_SESSION['success']);
  }
  ?>
</div>
</body>
</html>