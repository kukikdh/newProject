<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Student Productivity Tracker</title>
  <link rel="stylesheet" href="../public/css/base.css" />
  <link rel="stylesheet" href="../public/css/components.css" />
</head>
<body>
<div class="auth-content">
  <h2>Login</h2>
  <form method="post">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
  </form>
  <p><a href="signup.php">Don't have an account? Sign Up</a></p>
  <?php
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../config/db.php');
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['name'] = $user['name'];
      header('Location: ../pages/dashboard.php');
    } else {
      echo "<p style='color:red;'>Invalid email or password.</p>";
    }
  }
  ?>
</div>
</body>
</html>