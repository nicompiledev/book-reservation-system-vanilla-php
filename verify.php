<?php
/**
 * 
 */

require_once __DIR__ . '/config/app_config.php';
require_once BASE_PATH . '/config/db.php'; 

$verified = false;
$invalid = false;
$userEmail = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE verify_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        $verified = true;
    } else {
        $invalid = true;
        session_start();
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
    <title>Account Verification - Book Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
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
                <a href="<?= BASE_URL ?>views/login.php" class="button" style="display:block; text-align:center; margin-top:20px;">Go to Login</a>

            <?php elseif ($invalid): ?>
                <h2 class="error">❌ Invalid or expired verification token.</h2>
                <p>The verification link you used is not valid or has expired.</p>

                <?php if (!empty($userEmail)): ?>
                    <form method="POST" action="<?= BASE_URL ?>controllers/resend_verification.php">
                        <input type="hidden" name="email" value="<?= $userEmail ?>">
                        <button type="submit">Resend Verification Link</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="<?= BASE_URL ?>views/login.php">log in</a> first to resend the verification link.</p>
                <?php endif; ?>

                <p style="margin-top: 20px;">Already verified? <a href="<?= BASE_URL ?>views/login.php">Log in here</a></p>

            <?php else: ?>
                <h2 class="error">Token not found.</h2>
                <p>Please use the verification link sent to your email.</p>
                <p style="margin-top: 20px;"><a href="<?= BASE_URL ?>views/login.php">Go to Login</a></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const success = document.querySelector('.form-container .success');
            if (success) {
                setTimeout(() => {
                    success.style.display = 'none';
                    const next = success.nextElementSibling;
                    if (next && next.tagName === 'P') next.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>
</html>
