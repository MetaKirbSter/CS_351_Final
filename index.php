<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store user info in session
    $_SESSION['name'] = $_POST['name'] ?? '';
    $_SESSION['email'] = $_POST['email'] ?? '';

    // Redirect to login page after submitting the form
    header('Location: login.php');  // Adjust redirection as needed
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Tracker - Welcome</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Welcome to the Music Tracker!</h1>
        <p class="form-subtitle">Enter your details below to get started. Use the navigation links to explore the tracker or ratings pages!</p>

        <!-- Form Section -->
        <form action="" method="POST" class="info-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="form-button">Submit</button>
        </form>

        <!-- Thank You Message -->
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="thank-you-message">
                <p>Thank you for submitting the form!</p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($_POST['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_POST['email']); ?></p>
            </div>
        <?php endif; ?>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="music_tracker.php">Tracked Music</a>
            <a href="ratings.php">Ratings</a>
        </div>
    </div>
</body>
</html>
