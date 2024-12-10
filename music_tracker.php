<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Database Connection Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'muzak');
define('DB_USER', 'meta');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8mb4');

// PDO Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['field1'], $_POST['field2'], $_POST['field3'], $_POST['field4'], $_POST['field5'], $_POST['field6'])) {
        $fields = [
            'field1' => htmlspecialchars($_POST['field1']),
            'field2' => htmlspecialchars($_POST['field2']),
            'field3' => htmlspecialchars($_POST['field3']),
            'field4' => htmlspecialchars($_POST['field4']),
            'field5' => htmlspecialchars($_POST['field5']),
            'field6' => htmlspecialchars($_POST['field6']),
        ];

        $insert_sql = 'INSERT INTO listen_log (album, artist, release_date, listen_date, music_platform, collection_status) 
               VALUES (:field1, :field2, :field3, :field4, :field5, :field6)';
        $stmt = $pdo->prepare($insert_sql);
        $stmt->execute($fields);
    } elseif (isset($_POST['delete_id'])) {
        $delete_id = (int) $_POST['delete_id'];
        $delete_sql = 'DELETE FROM listen_log WHERE entry_id = :entry_id';
        $stmt = $pdo->prepare($delete_sql);
        $stmt->execute(['entry_id' => $delete_id]);
    }
}

// Fetch Entries
$sql = 'SELECT entry_id, album, artist, release_date, listen_date, music_platform, collection_status FROM listen_log';
$stmt = $pdo->query($sql);
$entries = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Listening Log</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="hero">
    <div class="hero-content">
        <h1>Welcome to Your Music Log</h1>
        <p>Track your favorite albums, listening habits, and much more!</p>
        <a href="#add-entry">
            <button>Add Your First Entry</button>
        </a>
    </div>
</div>

    <div class="container" id="add-entry">
        <h2>Add New Project</h2>
        <form action="listen_log.php" method="post">
            <label for="field1">Album Name:</label>
            <input type="text" id="field1" name="field1" required>
            <label for="field2">Artist:</label>
            <input type="text" id="field2" name="field2" required>
            <label for="field3">Release Date (YYYY-MM-DD):</label>
            <input type="text" id="field3" name="field3" required>
            <label for="field4">Listened On (YYYY-MM-DD):</label>
            <input type="text" id="field4" name="field4" required>
            <label for="field5">Music Platform:</label>
            <input type="text" id="field5" name="field5" required>
            <label for="field6">Collection Status:</label>
            <input type="text" id="field6" name="field6" required>
            <input type="submit" value="Add Entry">
        </form>
    </div>

    <div class="container">
        <h2>Music Log</h2>
        <table>
            <thead>
                <tr>
                    <th>Album Name</th>
                    <th>Artist</th>
                    <th>Release Date</th>
                    <th>Listen Date</th>
                    <th>Platform</th>
                    <th>Collection Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['album']); ?></td>
                    <td><?php echo htmlspecialchars($row['artist']); ?></td>
                    <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['listen_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['music_platform']); ?></td>
                    <td><?php echo htmlspecialchars($row['collection_status']); ?></td>
                    <td>
                        <form action="listen_log.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['entry_id']; ?>">
                            <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this entry?');">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="link-container">
        <a href="index.php">About</a>
        <a href="ratings.php">Ratings</a>
    </div>
</body>
</html>
