<?php
/**
 * Register Page View (register.php)
 *
 * This file renders the user registration form for the Book Reservation System.
 * Users can create a new account by providing their email, username, and password.
 * The form includes client-side validation for password strength and matching.
 *
 */

// Incluye el archivo de configuración de la aplicación para acceder a las constantes BASE_PATH y BASE_URL
require_once __DIR__ . '/../config/app_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Book Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="background-overlay"></div>
    <div class="main-content">
        <div class="welcome-message">
            <h1>Create Your Account</h1>
            <p>Register to start reserving books from our library.</p>
        </div>

        <div class="form-container">
            <form method="POST" action="<?= BASE_URL ?>controllers/register_process.php" onsubmit="return validateForm();">
                <label for="email">Email:</label>
                <input type="email" name="email" required placeholder="Enter your email">

                <label for="username">Username:</label>
                <input type="text" name="username" required placeholder="Choose a username">

                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required placeholder="Create a strong password">
                    <span class="toggle-password" onclick="togglePasswordVisibility(this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <small id="password-warning" class="error" style="display:none;"></small>

                <label for="confirm-password">Confirm Password:</label>
                <div class="password-container">
                    <input type="password" id="confirm-password" name="confirm_password" required placeholder="Repeat your password">
                    <span class="toggle-password" onclick="togglePasswordVisibility(this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <small id="confirm-warning" class="error" style="display:none;"></small>

                <button type="submit">Register</button>

                <?php if (isset($_GET['success'])): ?>
                    <p class="success">✅ Account created successfully!</p>
                <?php elseif (isset($_GET['error'])): ?>
                    <p class="error">❌ <?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
            </form>

            <p>Already have an account? <a href="<?= BASE_URL ?>views/login.php">Sign in here</a></p>
            <p>By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
        </div>
    </div>

    <script>
    // Toggle password visibility
    function togglePasswordVisibility(element) {
        const pwInput = element.parentElement.querySelector('input');
        const isPassword = pwInput.type === "password";
        pwInput.type = isPassword ? "text" : "password";
        // Change icon based on state
        element.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    }

    // Validate the form before submission
    function validateForm() {
        const pw = document.getElementById("password").value;
        const confirmPw = document.getElementById("confirm-password").value;

        // Check password strength
        if (!isPasswordStrong(pw)) {
            alert("Your password is too weak. It must be at least 8 characters long and include uppercase, lowercase, and a number.");
            return false;
        }

        // Check if passwords match
        if (pw !== confirmPw) {
            alert("Passwords do not match.");
            return false;
        }

        return true;
    }

    // Check if the password is strong
    function isPasswordStrong(password) {
        return password.length >= 8 &&
               /[A-Z]/.test(password) &&
               /[a-z]/.test(password) &&
               /\d/.test(password);
    }

    // Add real-time validation to password fields
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("confirm-password");
        const pwWarning = document.getElementById("password-warning");
        const confirmWarning = document.getElementById("confirm-warning");

        // Show warning if password is weak
        passwordInput.addEventListener("input", function () {
            if (!isPasswordStrong(this.value)) {
                pwWarning.style.display = "block";
                pwWarning.textContent = "Password must be at least 8 characters, with uppercase, lowercase and a number.";
            } else {
                pwWarning.style.display = "none";
            }
        });

        // Show warning if passwords do not match
        confirmInput.addEventListener("input", function () {
            if (passwordInput.value !== this.value) {
                confirmWarning.style.display = "block";
                confirmWarning.textContent = "Passwords do not match.";
            } else {
                confirmWarning.style.display = "none";
            }
        });
    });
    </script>
</body>
</html>