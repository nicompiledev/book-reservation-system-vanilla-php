<?php
session_start();
require_once '../config/db.php';
require_once '../config/mail_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validación del password
    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/\d/', $password)
    ) {
        header("Location: ../views/register.php?error=Password too weak");
        exit;
    }

    if ($password !== $confirm_password) {
        header("Location: ../views/register.php?error=Passwords do not match");
        exit;
    }

    // Verificar si el usuario o email ya existen
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);

    if ($stmt->fetch()) {
        header("Location: ../views/register.php?error=Username or email already exists");
        exit;
    }

    // Todo OK: crear usuario con token
    $token = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verify_token) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword, $token]);

    sendConfirmationEmail($email, $username, $token);

    header("Location: ../views/login.php?success=1");
    exit;
}
?>
