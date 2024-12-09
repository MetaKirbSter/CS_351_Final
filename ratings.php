<?php
$host = 'localhost'; 
$dbname = 'muzak'; 
$user = 'meta'; 
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Insert new rating
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['album']) && isset($_POST['rating']) && isset($_POST['comments'])) {
    $album = htmlspecialchars($_POST['album']);
    $rating = htmlspecialchars($_POST['rating']);
    $comments = htmlspecialchars($_POST['comments']);

    $insert_sql = 'INSERT INTO ratings (album, rating, comments) VALUES (:album, :rating, :comments)';
    $stmt_insert = $pdo->prepare($insert_sql);
    $stmt_insert->execute(['album' => $album, 'rating' => $rating, 'comments' => $comments]);
}

$sql = 'SELECT album, rating, comments FROM ratings';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Album Rating Screen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="hero">
        <h1>Welcome to the Album Rating Page</h1>
        <p>Share your thoughts and ratings on your favorite albums!</p>
    </div>

    <div class="container">
        <h2>Add a New Rating</h2>
        <form action="ratings.php" method="post">
            <label for="album">Album Name:</label>
            <input type="text" id="album" name="album" required>
            <label for="rating">Rating (1-10):</label>
            <input type="text" id="rating" name="rating" required>
            <label for="comments">Comments:</label>
            <input type="text" id="comments" name="comments" required>
            <input type="submit" value="Add Entry">
        </form>
    </div>

    <div class="table-container">
        <h2>Current Ratings</h2>
        <table>
            <thead>
                <tr>
                    <th>Album</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['album']); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['comments']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="link-container">
        <a href="index.php">About</a>
        <a href="music_tracker.php">Log</a>
    </div>

</body>
</html>
