<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Login check already done in auth_check.php
include(__DIR__ . '/../config/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Productivity Tracker</title>
  <link rel="stylesheet" href="/newProject/public/css/0-variables.css" />
  <link rel="stylesheet" href="/newProject/public/css/1-reset.css" />
  <link rel="stylesheet" href="/newProject/public/css/2-layout.css" />
  <link rel="stylesheet" href="/newProject/public/css/3-components.css" />
  <link rel="stylesheet" href="/newProject/public/css/4-admin.css" />
</head>
<body>
<?php include('sidebar.php'); ?>
<?php include('navbar.php'); ?>