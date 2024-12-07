<?php
session_start();
require_once 'auth.php';

// Check if user is logged in
if (!is_logged_in()) {
   header('Location: login.php');
   exit;
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Not Provided';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not Provided';

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
      
       $insert_sql = 'INSERT INTO listen_log (album, artist, release_date, listen_date, music_platform, collection_status) VALUES (:album, :artist, :release_date, :listen_date, :music_platform, :collection_status)';
       $stmt_insert = $pdo->prepare($insert_sql);
       $stmt_insert->execute(['album' => $album, 'artist' => $artist, 'release_date' => $release_date, 'listen_date' => $listen_date, 'music_platform' => $music_platform, 'collection_status' => $collection_status]);
   }
}

// Fetch all records without filtering
$sql = 'SELECT album, artist, release_date, listen_date, music_platform, collection_status FROM listen_log';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<div class="user-info">
        <h2>Welcome <?php echo htmlspecialchars($name); ?>!</h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
    </div>
   <meta charset="UTF-8">
   <title>Music Tracker Log!</title>
   <link rel="stylesheet" href="styles.css?v=1.0">
</head>
<body>
   <!-- Table section with container -->
   <div class="table-container">
       <h2>All Albums in Database</h2>
       <table class="half-width-left-align">
           <thead>
               <tr>
                   <th>Album</th>
                   <th>Artist</th>
                   <th>Release Date(YYYY-MM-DD)</th>
                   <th>Listen Date</th>
                   <th>Platform</th>
                   <th>Collection Status</th>
               </tr>
           </thead>
           <tbody>
               <?php while ($row = $stmt->fetch()): ?>
               <tr>
                   <td><?php echo htmlspecialchars($row['album']); ?></td>
                   <td><?php echo htmlspecialchars($row['artist']); ?></td>
                   <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                   <td><?php echo htmlspecialchars($row['listen_date']); ?></td>
                   <td><?php echo htmlspecialchars($row['music_platform']); ?></td>
                   <td><?php echo htmlspecialchars($row['collection_status']); ?></td>
                   <td>
                   </td>
               </tr>
               <?php endwhile; ?>
           </tbody>
       </table>
   </div>

   <!-- Form section with container -->
   <div class="form-container">
       <h2>Add Music Today!</h2>
       <form action="music_tracker.php" method="post">
           <label for="album">Album:</label>
           <input type="text" id="album" name="album" required>
           <br><br>
           <label for="artist">Artist:</label>
           <input type="text" id="artist" name="artist" required>
           <br><br>
           <label for="release_date">Release Date(YYYY-MM-DD):</label>
           <input type="text" id="release_date" name="release_date" required>
           <br><br>
           <label for="listen_date">Listen Date:</label>
           <input type="text" id="listen_date" name="listen_date" required>
           <br><br>
           <label for="music_platform">Platform:</label>
           <input type="text" id="music_platform" name="music_platform" required>
           <br><br>
           <label for="collection_status">Collection Status:</label>
           <input type="text" id="collection_status" name="collection_status" required>
           <br><br>
           <input type="submit" value="Track Album">
       </form>
   </div>
    <p><a href="index.php" style="color: #007bff; font-size: 16px; text-decoration: none; margin-top: 20px; display: inline-block;">About!</a></p>
    <p><a href="ratings.php" style="color: #007bff; font-size: 16px; text-decoration: none; margin-top: 20px; display: inline-block;">Ratings!</a></p>
</body>
</html>
