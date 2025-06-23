<?php
/**
 * Account Verification Script
 *
 * This script handles the verification of user accounts via a token sent to their email.
 *
 * Workflow:
 * - Checks if a 'token' parameter is present in the GET request.
 * - Looks up the user in the database with the provided verification token.
 * - If a matching user is found:
 * - Sets the user's 'is_verified' status to true.
 * - Removes the verification token from the database.
 * - Displays a success message.
 * - If no matching user is found:
 * - Displays an error message indicating an invalid or expired token.
 *
 * Dependencies:
 * - Requires a database connection from 'config/db.php'.
 * - Uses PDO for database operations.
 *
 * Variables:
 * - $verified (bool): Indicates if the account was successfully verified.
 * - $invalid (bool): Indicates if the token was invalid or expired.
 *
 * UI:
 * - Displays appropriate messages based on verification status.
 * - Provides a link to the login page after verification or error.
 * - Includes a form to resend the verification link if the token is invalid.
 */

// Include database connection
require_once 'config/db.php';
// Include application configuration (where BASE_URL is defined)
require_once 'config/app_config.php';

$verified = false; // Flag to indicate successful verification
$invalid = false;  // Flag to indicate invalid or expired token
$userEmail = '';   // Variable to store user email for resend form

// Check if 'token' parameter exists in the GET request
if (isset($_GET['token'])) {
    $token = $_GET['token']; // Get the token from URL

    // Prepare and execute query to find user with given token
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE verify_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // If user is found, update their status to verified and remove token
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        $verified = true; // Set verified flag
    } else {
        // If no user is found, set invalid flag
        $invalid = true;
        // If there's an active session and user ID is available, try to get email
        // This is important to allow resending the link to a logged-in user
        // whose token might have expired but is still authenticated.
        session_start(); // Ensure session is started to access $_SESSION
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $loggedInUser = $stmt->fetch();
            if ($loggedInUser) {
                $userEmail = htmlspecialchars($loggedInUser['email']);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification - Book Reservation</title>
    <!-- Link to stylesheet using BASE_URL -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <!-- Link to Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="background-overlay"></div>

    <div class="main-content">
        <div class="welcome-message">
            <h1>Account Verification</h1>
            <p>Confirm your access to the book reservation system.</p>
        </div>

        <div class="form-container">
            <?php if ($verified): ?>
                <h2 class="success">✅ Your account has been successfully verified!</h2>
                <p>You can now log in and enjoy all our features.</p>
                <!-- Link to login.php using BASE_URL -->
                <a href="<?php echo BASE_URL; ?>views/login.php" class="button" style="display: block; text-align: center; margin-top: 20px; text-decoration: none;">Go to Login</a>
            <?php elseif ($invalid): ?>
                <h2 class="error">❌ Invalid or expired verification token.</h2>
                <p>The verification link you used is not valid or has expired. Please try resending the link if you haven't verified your account yet.</p>

                <!-- Form to resend verification link using BASE_URL -->
                <form method="POST" action="<?php echo BASE_URL; ?>controllers/resend_verification.php">
                    <?php if (!empty($userEmail)): ?>
                        <input type="hidden" name="email" value="<?php echo $userEmail; ?>">
                        <button type="submit">Resend Verification Link</button>
                    <?php else: ?>
                        <!-- Link to login.php using BASE_URL -->
                        <p>To resend the link, please <a href="<?php echo BASE_URL; ?>views/login.php">log in</a> first if you haven't already, or contact support.</p>
                    <?php endif; ?>
                </form>
                <!-- Link to login.php using BASE_URL -->
                <p style="margin-top: 20px;">Already have a verified account? <a href="<?php echo BASE_URL; ?>views/login.php">Log in here</a></p>
            <?php else: ?>
                <h2 class="error">Token not found.</h2>
                <p>It seems no verification token was provided. If you came here from an email link, make sure it's complete.</p>
                <!-- Link to login.php using BASE_URL -->
                <p style="margin-top: 20px;">Please <a href="<?php echo BASE_URL; ?>views/login.php">log in</a> to get a new link or to access your account.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // If there's a success message, hide it after 5 seconds
        document.addEventListener("DOMContentLoaded", function() {
            const successHeading = document.querySelector('.form-container .success');
            if (successHeading) {
                setTimeout(() => {
                    successHeading.style.display = 'none';
                    // Hide the next paragraph too (if it's the success message paragraph)
                    const nextSibling = successHeading.nextElementSibling;
                    if (nextSibling && nextSibling.tagName === 'P') {
                        nextSibling.style.display = 'none';
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>