<?php

session_start();
require_once 'auth.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

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

// Handle project search
$search_results = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $search_sql = 'SELECT id, album, artist, release_date, listen_date, music_platform, collection_status FROM listen_log WHERE title LIKE :search';
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute(['search' => $search_term]);
    $search_results = $search_stmt->fetchAll();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['album']) && isset($_POST['artist']) && isset($_POST['release_date']) && isset($_POST['listen_date']) && isset($_POST['music_platform']) && isset($_POST['collection_status'])) {
        // Insert new entry
        $album = htmlspecialchars($_POST['album']);
        $artist = htmlspecialchars($_POST['artist']);
        $release_date = htmlspecialchars($_POST['release_date']);
        $listen_date = htmlspecialchars($_POST['listen_date']);
        $music_platform = htmlspecialchars($_POST['music_platform']);
        $collection_status = htmlspecialchars($_POST['collection_status']);
        
        $insert_sql = 'INSERT INTO projects (album, artist, release_date, listen_date, music_platform, collection_status) VALUES (:album, :artist, :release_date, :listen_date, :music_platform, :collection_status)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['album' => $album, 'artist' => $artist, 'release_date' => $release_date, 'listen_date' => $listen_date, 'music_platform' => $music_platform, 'collection_status' => $collection_status]);
    } 
}

// Get all projects for main table
//comment again/
$sql = 'SELECT id, album, artist, release_date, listen_date, music_platform, collection_status, FROM projects';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music Tracker Log!</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">Music Tracker Log</h1>
        <p class="hero-subtitle">"Because RateYourMusic is too pretentious!"</p>
        
        <!-- Search moved to hero section -->
        <div class="hero-search">
            <h2>Search for an album/EP:</h2>
            <form action="" method="GET" class="search-form">
                <label for="search">Search by Title:</label>
                <input type="text" id="search" name="search" required>
                <input type="submit" value="Search">
            </form>
            
            <?php if (isset($_GET['search'])): ?>
                <div class="search-results">
                    <h3>Search Results</h3>
                    <?php if ($search_results && count($search_results) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Album</th>
                                    <th>Artist</th>
                                    <th>Release Date</th>
                                    <th>Listen Date</th>
                                    <th>Platform</th>
                                    <th>Collection Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($search_results as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['album']); ?></td>
                                    <td><?php echo htmlspecialchars($row['artist']); ?></td>
                                    <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['listen_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['music_platform']); ?></td>
                                    <td><?php echo htmlspecialchars($row['collection_status']); ?></td>
                                    <td>
                                        <form action="index.php" method="post" style="display:inline;">
                                            <input type="submit" value="Add!">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No projects found matching your search.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table section with container -->
    <div class="table-container">
        <h2>All Albums in Database</h2>
        <table class="half-width-left-align">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Album</th>
                    <th>Artist</th>
                    <th>Release Date</th>
                    <th>Listen Date</th>
                    <th>Platform</th>
                    <th>Collection Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['album']); ?></td>
                    <td><?php echo htmlspecialchars($row['artist']); ?></td>
                    <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['listen_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['music_platform']); ?></td>
                    <td><?php echo htmlspecialchars($row['collection_status']); ?></td>
                    <td>
                        <form action="index5.php" method="post" style="display:inline;">
                            <input type="submit" value="Track!">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Form section with container -->
    <div class="form-container">
        <h2>Add Music Today!</h2>
        <form action="index5.php" method="post">
            <label for="album">Album:</label>
            <input type="text" id="album" name="album" required>
            <br><br>
            <label for="artist">Artist:</label>
            <input type="text" id="artist" name="artist" required>
            <br><br>
            <label for="publisher">Release Date:</label>
            <input type="text" id="release_date" name="release_date" required>
            <br><br>
            <input type="submit" value="Track album">
        </form>
    </div>
</body>
</html>