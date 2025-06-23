<?php
session_start();

// If user is logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: views/dashboard.php");
    exit;
}

// Otherwise, go to login
header("Location: views/login.php");
exit;
