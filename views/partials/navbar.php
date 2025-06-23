<?php
if (!isset($_SESSION)) session_start();

$username = htmlspecialchars($_SESSION['username'] ?? 'User');
$profileImage = $_SESSION['profile_image'] ?? '../public/images/default-avatar.png';
?>
<nav class="navbar">
    <div class="navbar-header">
        <a href="dashboard.php" class="nav-logo">📚 Book Reservation</a>
        <button class="navbar-toggle" id="burger-btn" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <div class="navbar-collapse" id="navbar-menu">
        <div class="nav-left">
            <a href="dashboard.php">Dashboard</a>
            <a href="#">Books</a>
            <a href="#">Reservations</a>
        </div>

        <div class="nav-right">
            <a href="profile.php" class="user-profile-link">
                <span class="username">
                    <?= $username ?>
                </span>
                <?php if (!empty($_SESSION['profile_image']) && file_exists($_SESSION['profile_image'])): ?>
                <img src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="Profile" class="profile-pic">
                <?php else: ?>
                <div class="default-avatar-icon" title="No profile image">
                    <!-- Ícono de usuario en SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="35" height="35">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 20c0-4 4-6 8-6s8 2 8 6" fill="none" stroke="white" stroke-width="2" />
                    </svg>
                </div>
                <?php endif; ?>

            </a>

            <a href="../controllers/logout.php" class="logout-link">Logout</a>
        </div>
    </div>
</nav>