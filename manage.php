<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Phrases</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #4caf50;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        form {
            max-width: 400px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
        a {
            display: block;
            text-align: center;
            margin: 20px auto;
            color: #4caf50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Manage Phrases</h1>

    <?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Database configuration
    $host = 'localhost';
    $dbname = '351final';
    $user = 'root';
    $pass = 'mysql';
    $charset = 'utf8mb4';

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phrases = trim($_POST['phrases']); // Get the submitted phrase

        if (!empty($phrases)) {
            try {
                // Establish a database connection
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Insert the phrase into the database
                $stmt = $pdo->prepare('INSERT INTO data (phrases) VALUES (:phrases)');
                $stmt->execute(['phrases' => $phrases]);

                echo '<p class="message">Phrase added successfully!</p>';
            } catch (PDOException $e) {
                echo '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p class="message">Please enter a phrase.</p>';
        }
    }
    ?>

    <form method="post">
        <label for="phrases">Enter a New Phrase:</label>
        <input type="text" id="phrases" name="phrases" required>
        <button type="submit">Add Phrase</button>
    </form>

    <a href="index.php">Go Back to Home</a>
</body>
</html>
