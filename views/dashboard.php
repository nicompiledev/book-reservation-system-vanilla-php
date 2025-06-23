<?php
/**
 * Dashboard Page for Book Reservation System
 *
 * This script displays the dashboard for logged-in users. It performs the following:
 * - Includes the database configuration.
 * - Starts a session and checks if the user is logged in; redirects to login if not.
 * - Retrieves the logged-in user's username, email, and verification status from the database.
 * - Displays a personalized welcome message.
 * - Shows a success message if a verification email was recently resent.
 * - If the user is not verified, displays a warning and provides a form to resend the verification email.
 * - If the user is verified, displays a confirmation message.
 * - Provides a logout button.
 * - Contains a placeholder section for books or reservations content.
 *
 * Security:
 * - User input and session data are sanitized before output to prevent XSS.
 *
 * Dependencies:
 * - Requires a valid database connection in '../config/db.php'.
 * - Relies on session variables: 'user_id' and 'username'.
 * - Uses external CSS from '../public/css/style.css'.
 *
 * Usage:
 * - Place this file in the 'views' directory of the Book Reservation System.
 * - Ensure the database connection is properly configured in '../config/db.php'.
 * - Access this page after a user logs in to view their dashboard.
 * - The page should be accessed via a web server that supports PHP.
 * - Ensure session management is properly configured in your PHP environment.
 * @package   BookReservation
 * @author    Your Name
 * @copyright Copyright (c) 2024
 * @license   MIT License
 * @version   1.0
 */

// Include database configuration
require_once '../config/db.php';
// Start the session
session_start();

// Redirect if there is no active session (user not logged in)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the username from session, sanitize for output
$username = htmlspecialchars($_SESSION['username'] ?? 'User');

// Query to check if the user is verified and get their email
$stmt = $pdo->prepare("SELECT is_verified, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Store verification status and email
$isVerified = $user['is_verified'];
$email = $user['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Book Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="../public/css/style.css?v=<?= time(); ?>">

</head>

<body>
    <div class="background-overlay">

    </div>

    <?php include 'partials/navbar.php'; ?>

    <div class="dashboard-container">
        <!-- Welcome message with highlighted username -->
        <h1 class="welcome-title">Welcome back, <span class="highlight"><?= $username?></span><span class="exclamation">!</span></h1>
        <p>You can now browse and manage your book reservations.</p>

        <!-- Show success message if verification email was resent -->
        <?php if (isset($_GET['resent'])): ?>
        <p class="success">✅ Verification email has been resent to
            <?= htmlspecialchars($email) ?>.
        </p>
        <?php endif; ?>

        <!-- Show warning if user is not verified, with option to resend verification email -->
        <?php if (!$isVerified): ?>
        <div class="warning-box">
            <p>⚠️ Please confirm your email address to enable book reservations.</p>
            <form method="POST" action="../controllers/resend_verification.php">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <button type="submit">Resend verification email</button>
            </form>
        </div>
        <?php else: ?>
        <!-- Show success message if user is verified -->
        <p class="success">✅ Your email is verified. You can make reservations.</p>
        <?php endif; ?>

        <!-- Logout button
        <a href="../controllers/logout.php" class="logout-button">Logout</a> -->

        <!-- Placeholder for books or reservations content -->
        <div class="dashboard-content">
            <h2>📚 Books or Reservations will go here</h2>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('burger-btn');
            const navbar = document.querySelector('.navbar');
            const collapseMenu = document.getElementById('navbar-menu');

            toggleButton.addEventListener('click', () => {
                navbar.classList.toggle('active');
                collapseMenu.classList.toggle('active');
            });
        });
    </script>

</body>

</html>