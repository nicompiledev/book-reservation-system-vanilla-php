<?php
/**
 * Login Page View for Book Reservation System
 *
 * This file renders the login page where users can sign in to access the Book Reservation System.
 *
 * Features:
 * - Displays a welcome message and prompts users to sign in or register.
 * - Shows success message if account creation was successful.
 * - Shows error message if login fails, with error details sanitized for security.
 * - Login form with fields for username and password.
 * - Password field includes a toggle to show/hide password using JavaScript and Font Awesome icons.
 * - Submit button displays a loading state ("Signing in...") when the form is submitted.
 * - Success messages automatically disappear after 5 seconds.
 * - Link provided for users to register if they do not have an account.
 *
 * Security:
 * - Sanitizes output to prevent XSS attacks.
 * - Uses POST method for form submission to protect sensitive data.
 *
 * Dependencies:
 * - External CSS for styling (`../public/css/style.css`)
 * - Font Awesome for icons
 * - JavaScript for UI enhancements (loading indicator, password toggle, auto-hide messages)
 *
 * Usage:
 * - Place this file in the `views` directory.
 * - Ensure the login form action points to the correct login processing script.
 * - Requires PHP to process GET parameters for success and error messages.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Book Reservation</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="background-overlay"></div>

    <div class="main-content">
        <div class="welcome-message">
        <h1>Welcome to the Book Reservation System!</h1>
        <p>Access your favorite books anytime.<br> Please sign in or register to continue.</p>
    </div>

    <div class="form-container">
        <h2>Sign in</h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="success" id="success-message">✅ Account created! Please sign in.</p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p class="error">❌ <?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form method="POST" action="../controllers/login_process.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required placeholder="Enter your username">

            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required placeholder="Enter your password">
                <span class="toggle-password" onclick="togglePassword(this)">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <button type="submit">Sign in</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    </div>

    

    <script>
    // Show loading indicator on submit
    document.querySelector("form").addEventListener("submit", function () {
        const button = this.querySelector("button");
        button.disabled = true;
        button.innerHTML = "Signing in...";
    });

    // Hide success message after 5 seconds
    document.addEventListener("DOMContentLoaded", function () {
        const successMessage = document.getElementById("success-message");
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = "none";
            }, 5000);
        }
    });

    // Toggle password visibility
    function togglePassword(element) {
        const pwInput = element.parentElement.querySelector('input');
        const isPassword = pwInput.type === "password";
        
        pwInput.type = isPassword ? "text" : "password";
        element.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    }
    </script>
</body>
</html>