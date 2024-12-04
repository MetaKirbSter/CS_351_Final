<?php
$host = 'localhost'; 
$dbname = 'listen_log'; 
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['field1']) && isset($_POST['field2'])  && isset($_POST['field3']) && isset($_POST['field4']) && isset($_POST['field5']) && isset($_POST['field6'])) {
        // Insert new entry
        $field1 = htmlspecialchars($_POST['field1']);
        $field2 = htmlspecialchars($_POST['field2']);
        $field3 = htmlspecialchars($_POST['field3']);
        $field4 = htmlspecialchars($_POST['field4']);
        $field5 = htmlspecialchars($_POST['field5']);
        $field6 = htmlspecialchars($_POST['field6']);
    
        
        $insert_sql = 'INSERT INTO data1 (field1, field2, field3, field4, field5, field6) VALUES (:field1, :field2, :field3, :field4, :field5, :field6)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['field1' => $field1, 'field2' => $field2, 'field3' => $field3, 'field4' => $field4, 'field5' => $field5, 'field6' => $field6]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        $delete_sql = 'DELETE FROM data1 WHERE entry_id = :entry_id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['entry_id' => $delete_id]);
    }
}

$sql = 'SELECT field1, field2, field3, field4, field5, field6 FROM listen_log';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music Listening Log</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Add New Project</h2>
    <form action="index3.php" method="post">
        <label for="field1">Album Name:</label>
        <input type="text" id="field1" name="field1" required>
        <br><br>
        <label for="field2">Artist:</label>
        <input type="text" id="field2" name="field2" required>
        <br><br>
        <label for="field3">Release Date:</label>
        <input type="text" id="field3" name="field3" required>
        <br><br>
        <label for="field4">Listened On:</label>
        <input type="text" id="field4" name="field4" required>
        <br><br>
        <label for="field5">Music Platform:</label>
        <input type="text" id="field5" name="field5" required>
        <br><br>
        <label for="field5">Collection Status:</label>
        <input type="text" id="field6" name="field6" required>
        <br><br>
        <input type="submit" value="Add Entry">
    </form>

    <h1>Music Data Listing from Table ''</h1>
    <table class="half-width-left-align">
        <thead>
            <tr>
                <th>Album Name</th>
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
                <td><?php echo htmlspecialchars($row['field1']); ?></td>
                <td><?php echo htmlspecialchars($row['field2']); ?></td>
                <td><?php echo htmlspecialchars($row['field3']); ?></td>
                <td><?php echo htmlspecialchars($row['field4']); ?></td>
                <td><?php echo htmlspecialchars($row['field5']); ?></td>
                <td><?php echo htmlspecialchars($row['field6']); ?></td>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
