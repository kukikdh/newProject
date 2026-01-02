<?php
session_start();
session_destroy(); //removes all the session
header('Location: ../pages/home.php?message=logout_success'); //goes back to the home page
?>