<?php
session_start();
// Check if logged in, else redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Productivity Tracker</title>
  <link rel="stylesheet" href="../public/css/00-variables.css" />
  <link rel="stylesheet" href="../public/css/01-reset.css" />
  <link rel="stylesheet" href="../public/css/02-layout.css" />
  <link rel="stylesheet" href="../public/css/03-components.css" />
</head>
<body>
<?php include('sidebar.php'); ?>
<?php include('navbar.php'); ?>