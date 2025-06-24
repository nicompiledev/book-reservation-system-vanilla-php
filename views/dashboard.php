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
 */

// Include the application config file first so constants are available
require_once __DIR__ . '/../config/app_config.php';
require_once BASE_PATH . '/config/db.php'; // Use BASE_PATH to include db.php

session_start();

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "views/login.php");
    exit;
}

// Get the username from session, or use 'User' as fallback
$username = htmlspecialchars($_SESSION['username'] ?? 'User');

// Fetch user verification status, email, and profile image from database
$stmt = $pdo->prepare("SELECT is_verified, email, profile_image FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$isVerified = $user['is_verified'];
$email = $user['email'];
$profileImage = $user['profile_image'];

// Check if profile image exists, otherwise use default image
if (empty($profileImage) || !file_exists(BASE_PATH . '/uploads/' . $profileImage)) {
    $profileImage = 'default_profile.png';
}

// Get the total number of reservations for the user
$resStmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ?");
$resStmt->execute([$_SESSION['user_id']]);
$totalReservations = $resStmt->fetchColumn();

// Fetch actual reservations for display
$reservations = [];
if ($totalReservations > 0) {
    $resStmt = $pdo->prepare("SELECT title, author FROM reservations WHERE user_id = ?");
    $resStmt->execute([$_SESSION['user_id']]);
    $reservations = $resStmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Book Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Main stylesheet with cache busting -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css?v=<?= time(); ?>">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="background-overlay"></div>
    <!-- Include navigation bar -->
    <?php include BASE_PATH . '/views/partials/navbar.php'; ?> 
    <div class="dashboard-container">
        <div class="user-info-grid">
            <div class="user-details">
                <!-- Display welcome message and total reservations -->
                <p><i class="fas fa-user-circle"></i> <span class="label">¡Welcome back!</span> <strong>
                        <?= $username ?>
                    </strong></p>
                <p><i class="fas fa-book"></i> <span class="label">Total reserves:</span> <strong>
                        <?= $totalReservations ?>
                    </strong></p>
            </div>
            <div class="user-photo">
                <!-- Show profile image or default avatar -->
                <?php if ($profileImage && $profileImage !== 'default_profile.png'): ?>
                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($profileImage) ?>" alt="Profile Picture"
                    class="profile-summary-pic">
                <?php else: ?>
                <div class="default-avatar-icon"><i class="fas fa-user"></i></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- If user is not verified, show warning and resend verification form -->
        <?php if (!$isVerified): ?>
        <div class="warning-box">
            <p>⚠️ Please confirm your email address to enable book reservations.</p>
            <form method="POST" action="<?= BASE_URL ?>controllers/resend_verification.php">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <button type="submit">Resend verification email</button>
            </form>
        </div>
        <?php else: ?>
        <!-- If user is verified, show confirmation message -->
        <p class="success" id="verified-message">✅ Your email is verified. You can make reservations.</p>
        <?php endif; ?>

        <h2 class="section-title">My reserves 📚</h2>

        <!-- Reservations table -->
        <table class="reservation-table">
            <thead>
                <tr>
                    <th data-label="Title">Title</th>
                    <th data-label="Author">Author</th>
                    <th data-label="Action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($totalReservations > 0): ?>
                <?php foreach ($reservations as $row): ?>
                <tr>
                    <td data-label="Title">
                        <?= htmlspecialchars($row['title']) ?>
                    </td>
                    <td data-label="Author">
                        <?= htmlspecialchars($row['author']) ?>
                    </td>
                    <td data-label="Action"><a href="#" class="delete-btn">delete</a></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3">No reservations found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Include footer -->
    <?php include BASE_PATH . '/views/partials/footer.php'; ?> 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Navbar burger menu toggle for mobile
            const toggleButton = document.getElementById('burger-btn');
            const navbar = document.querySelector('.navbar');
            const collapseMenu = document.getElementById('navbar-menu');

            toggleButton?.addEventListener('click', () => {
                navbar.classList.toggle('active');
                collapseMenu.classList.toggle('active');
            });

            // Hide verified message after 5 seconds
            const verifiedMsg = document.getElementById('verified-message');
            if (verifiedMsg) {
                setTimeout(() => {
                    verifiedMsg.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>

</html>