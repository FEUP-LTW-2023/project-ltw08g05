<?php
    require_once (__DIR__ . '/../templates/Tcommon.php');
    require_once("../../src/controllers/LoginController.php");
    session_start();

    drawHeader();
?>
    <script src="../scripts/login.js"></script>

    <!-- records to be removed when the time comes -->
    <?php LoginController::showRecordsFromDatabase(); ?>

    <section id="login" class="centered">
        <h2>Login</h2>
        <p>\2630</p>
        <form>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"><br>
            <label for="remember">Remember me:</label>
            <input type="checkbox" name="remember" id="remember"><br>
            <label for="submit"></label>
            <button formaction="/src/controllers/LoginController.php" formmethod="post" disabled hover-text="Fields are not filled" style="vertical-align:middle"><span>Login</span></button>
        </form>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </section>
<?php
    drawFooter();
?>
