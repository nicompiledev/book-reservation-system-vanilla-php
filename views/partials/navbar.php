<?php

// Start the session if it hasn't been started yet. This is crucial for accessing $_SESSION.
if (!isset($_SESSION)) {
    session_start();
}

// Get the username from the session, or use 'User' as a default. Escape for HTML output.
$username = htmlspecialchars($_SESSION['username'] ?? 'User');

// Get the profile image filename from the session, or set to null if not present.
$profileImage = $_SESSION['profile_image'] ?? null;

// Prepare the full filesystem path to the profile image for existence check.
$fullProfileImagePath = '';
if (!empty($profileImage)) {
    $fullProfileImagePath = BASE_PATH . '/uploads/' . $profileImage;
}

?>
<nav class="navbar">
    <div class="navbar-header">
        <!-- Logo and burger button for mobile navigation -->
        <a href="<?= BASE_URL ?>views/dashboard.php" class="nav-logo">📚 Book Reservation</a>
        <button class="navbar-toggle" id="burger-btn" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <div class="navbar-collapse" id="navbar-menu">
        <div class="nav-left">
            <!-- Navigation links on the left side -->
            <a href="<?= BASE_URL ?>views/dashboard.php">Dashboard</a>
            <a href="<?= BASE_URL ?>views/books.php">Books</a>
            <a href="<?= BASE_URL ?>views/reservations.php">Reservations</a>
        </div>

        <div class="nav-right">
            <!-- User profile section on the right side -->
            <a href="<?= BASE_URL ?>views/profile.php" class="user-profile-link">
                <span class="username">
                    <?= $username ?>
                </span>
                <?php
                // Check if a profile image is set and exists in the filesystem
                if (!empty($profileImage) && file_exists($fullProfileImagePath)): ?>
                    <!-- Display the user's profile image -->
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="profile-pic">
                <?php else: ?>
                    <!-- Display a default avatar icon if no profile image is available -->
                    <div class="default-avatar-icon" title="No profile image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="35" height="35">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 4-6 8-6s8 2 8 6" fill="none" stroke="white" stroke-width="2" />
                        </svg>
                    </div>
                <?php endif; ?>
            </a>
            <!-- Logout link -->
            <a href="<?= BASE_URL ?>controllers/logout.php" class="logout-link">Logout</a>
        </div>
    </div>
</nav>