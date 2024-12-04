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
    <link rel="stylesheet" href="styles.css?v=1.0">
</head>
<body>
    <h2>Add New Rating</h2>
    <form action="ratings.php" method="post">
        <label for="album">Album Name:</label>
        <input type="text" id="album" name="album" required>
        <br><br>
        <label for="rating">Rating:</label>
        <input type="text" id="rating" name="rating" required>
        <br><br>
        <label for="comments">Comments:</label>
        <input type="text" id="comments" name="comments" required>
        <br><br>
        <input type="submit" value="Add Entry">
    </form>

    <h1>Ratings Data</h1>
    <table class="half-width-left-align">
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
                    <form action="ratings.php" method="post" style="display:inline;">
                    </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="index.php" style="color: #007bff; font-size: 16px; text-decoration: none; margin-top: 20px; display: inline-block;">About!</a></p>
    <p><a href="music_tracker.php" style="color: #007bff; font-size: 16px; text-decoration: none; margin-top: 20px; display: inline-block;">Log!</a></p>
</body>
</html>
