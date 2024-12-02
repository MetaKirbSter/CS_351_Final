<?php
$host = 'localhost'; 
$dbname = 'ratings'; 
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
    if (isset($_POST['field1']) && isset($_POST['field2'])  && isset($_POST['field3'])) {
        // Insert new entry
        $field1 = htmlspecialchars($_POST['field1']);
        $field2 = htmlspecialchars($_POST['field2']);
        $field3 = htmlspecialchars($_POST['field3']);

        $insert_sql = 'INSERT INTO data1 (field1, field2, field3) VALUES (:field1, :field2, :field3)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['field1' => $field1, 'field2' => $field2, 'field3' => $field3]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        $delete_sql = 'DELETE FROM data1 WHERE entry_id = :entry_id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['entry_id' => $delete_id]);
    }
}

$sql = 'SELECT entry_id, field1, field2, field3 FROM ratings';
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
    <h2>Add New Rating</h2>
    <form action="index3.php" method="post">
        <label for="field1">Album Name:</label>
        <input type="text" id="field1" name="field1" required>
        <br><br>
        <label for="field2">Rating:</label>
        <input type="text" id="field2" name="field2" required>
        <br><br>
        <label for="field3">Comments:</label>
        <input type="text" id="field3" name="field3" required>
        <br><br>
        <input type="submit" value="Add Entry">
    </form>

    <h1>Project Data Listing from Table 'data1'</h1>
    <table class="half-width-left-align">
        <thead>
            <tr>
                <th>Entry ID</th>
                <th>Album</th>
                <th>Rating</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['entry_id']); ?></td>
                <td><?php echo htmlspecialchars($row['field1']); ?></td>
                <td><?php echo htmlspecialchars($row['field2']); ?></td>
                <td><?php echo htmlspecialchars($row['field3']); ?></td>
                <td>
                    <form action="index3.php" method="post" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $row['entry_id']; ?>">
                        <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this entry?');">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
