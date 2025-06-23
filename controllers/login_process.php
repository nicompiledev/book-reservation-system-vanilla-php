<?php
file_put_contents("test.txt", "Login script reached: " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // echo "Password received: " . $password;
    // exit;

    // Fetch user from database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Debug output
    // var_dump($user);
    // if ($user) {
    //     echo '<br>Password match: ';
    //     var_dump(password_verify($password, $user['password']));
    // } else {
    //     echo '<br>No user found.';
    // }
    // exit;

    if ($user && password_verify($password, $user['password'])) {
        // Success: set session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../index.php");
        exit;
    } else {
        // Fail: redirect back with error
        header("Location: ../views/login.php?error=Invalid username or password");
        exit;
    }
}
?>
