<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php

        $db = new PDO('sqlite:../../database/database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('SELECT * FROM User');
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            echo $row['email'] . ' ' . $row['password'] . '<br>';
        }
        

        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<p>' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
    ?>
    <form method="post" action="action_login.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" <?php if (isset($_SESSION['email'])) echo 'value="' . $_SESSION['email'] . '"'; ?>><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" ><br>
        <label for="remember">Remember me:</label>
        <input type="checkbox" name="remember" id="remember" ><br>
        <label for="submit"></label>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
