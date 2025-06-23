<?php
session_start();
// ...existing code...
?>

<h2>Bienvenido, <?php echo $_SESSION['username']; ?></h2>

<h3>Update Profile Picture</h3>
<form action="../controllers/upload_profile_image.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="profile_image" accept="image/*" required>
    <button type="submit">Upload</button>
</form>

<!-- ...existing code... -->