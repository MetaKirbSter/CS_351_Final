<!DOCTYPE HTML>
<html>
    <head>
        <title>Hello! I wanted to create a small music listening/ratings tracker. To navigate to them, press "Index" or "Ratings"!</title>
    </head>
    <body>
        <h2>Enter your info below!</h2>
        <!--GPT assisted me with forms!-->
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <input type="submit" value="Submit">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            
            echo "<p>Thank you for submitting the form!</p>";
            echo "<p>Name: $name</p>";
            echo "<p>Email: $email</p>";
        }
        ?>

        <p><a href="music_tracker.php" style="color: #007bff; font-size: 16px; text-decoration: none; margin-top: 20px; display: inline-block;">Tracked Music!</a></p>
        <p><a href="ratings.php" style="color: #007bff; font-size: 16px; text-decoration: none; margin-top: 20px; display: inline-block;">Ratings!</a></p>
    </body>
</html>
