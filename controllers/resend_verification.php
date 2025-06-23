<?php
/**
 * Handles the process of resending a verification email to the user.
 *
 * This script performs the following actions:
 * - Starts a session and includes required configuration and helper files.
 * - Checks if the request method is POST and if the user is logged in via session.
 * - Retrieves the user's email from the POST data.
 * - Generates a new verification token using a cryptographically secure method.
 * - Updates the user's verification token in the database.
 * - Retrieves the username of the current user from the database.
 * - Sends a confirmation email with the new verification token to the user's email address.
 * - Redirects the user to the dashboard with a query parameter indicating the email was resent.
 *
 * Dependencies:
 * - Requires a valid PDO database connection in '../config/db.php'.
 * - Requires a mail helper function 'sendConfirmationEmail' in '../config/mail_helper.php'.
 *
 * Security:
 * - Ensures only authenticated users can trigger the resend action.
 * - Uses prepared statements to prevent SQL injection.
 *
 * @package BookReservation
 * @subpackage Controllers
 */
session_start(); // Start the session to access session variables
require_once '../config/db.php'; // Include database connection
require_once '../config/mail_helper.php'; // Include mail helper functions

// Check if the request is POST and the user is authenticated
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $email = $_POST['email']; // Get the user's email from POST data

    // Generate a new verification token using a secure random generator
    $token = bin2hex(random_bytes(16));

    // Update the user's verification token in the database
    $stmt = $pdo->prepare("UPDATE users SET verify_token = ? WHERE id = ?");
    $stmt->execute([$token, $_SESSION['user_id']]);

    // Retrieve the username of the current user from the database
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Send the confirmation email with the new token
    sendConfirmationEmail($email, $user['username'], $token);

    // Redirect to the dashboard with a flag indicating the email was resent
    header("Location: ../views/dashboard.php?resent=1");
    exit;
}
