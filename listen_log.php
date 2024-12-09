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

        $insert_sql = 'INSERT INTO data1 (field1, field2, field3, field4, field5, field6) 
                       VALUES (:field1, :field2, :field3, :field4, :field5, :field6)';
        $stmt = $pdo->prepare($insert_sql);
        $stmt->execute($fields);
    } elseif (isset($_POST['delete_id'])) {
        $delete_id = (int) $_POST['delete_id'];
        $delete_sql = 'DELETE FROM data1 WHERE entry_id = :entry_id';
        $stmt = $pdo->prepare($delete_sql);
        $stmt->execute(['entry_id' => $delete_id]);
    }
}

// Fetch Entries
$sql = 'SELECT entry_id, field1, field2, field3, field4, field5, field6 FROM data1';
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .hero {
            background: url('topster.jpg') no-repeat center center / cover, linear-gradient(to bottom, #6c63ff, #a1a1ff);
            color: white;
            text-align: center;
            padding: 3rem 0;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .hero p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }
        .hero button {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            border-radius: 5px;
            background-color: white;
            color: #6c63ff;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .container {
            margin: 2rem auto;
            padding: 1rem;
            max-width: 800px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 0.8rem;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #6c63ff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }
        input[type="submit"] {
            background-color: #6c63ff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to Your Music Log</h1>
        <p>Track your favorite albums, listening habits, and much more!</p>
        <a href="#add-entry"><button>Add Your First Entry</button></a>
    </div>

    <div class="container" id="add-entry">
        <h2>Add New Project</h2>
        <form action="listen_log.php" method="post">
            <label for="field1">Album Name:</label>
            <input type="text" id="field1" name="field1" required>
            <label for="field2">Artist:</label>
            <input type="text" id="field2" name="field2" required>
            <label for="field3">Release Date:</label>
            <input type="text" id="field3" name="field3" required>
            <label for="field4">Listened On:</label>
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
                    <td><?php echo htmlspecialchars($row['field1']); ?></td>
                    <td><?php echo htmlspecialchars($row['field2']); ?></td>
                    <td><?php echo htmlspecialchars($row['field3']); ?></td>
                    <td><?php echo htmlspecialchars($row['field4']); ?></td>
                    <td><?php echo htmlspecialchars($row['field5']); ?></td>
                    <td><?php echo htmlspecialchars($row['field6']); ?></td>
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
</body>
</html>
