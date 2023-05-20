<?php
    require_once (__DIR__ . '/../templates/Tcommon.php');
    require_once("../../src/controllers/LoginController.php");
    session_start();
    
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = generate_random_token();
    }


    drawHeader($db);
?>
    <head>
    <link href="../styles/login.css" rel="stylesheet">
    </head>
    <script src="../scripts/login.js"></script>

    <section id="login" class="centered">
        <h2>Login</h2>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"><br>
            <label for="remember">Remember me:</label>
            <input type="checkbox" name="remember" id="remember"><br>
            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf'];?>">
            <label for="submit"></label>
            <button formaction="/src/controllers/LoginController.php" formmethod="post" disabled hover-text="Fields are not filled" style="vertical-align:middle"><span>Login</span></button>
        </form>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p style="background-color: red;"><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </section>
<?php
    drawFooter();
?>
