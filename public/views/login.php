<?php
    require_once("../../src/controllers/LoginController.php");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

    <form method="post" action="/src/controllers/LoginController.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" 
        <?php if (isset($_SESSION['email'])) echo 'value="' . $_SESSION['email'] . '"'; ?>><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"><br>
        <label for="remember">Remember me:</label>
        <input type="checkbox" name="remember" id="remember"><br>
        <label for="submit"></label>
        <input type="submit" value="Submit">
    </form>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

</body>
</html>
