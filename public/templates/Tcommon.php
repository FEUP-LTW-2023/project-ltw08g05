<?php
declare(strict_types=1);  // strict_types declaration must be the very first statement in the script

/**
 * start a session and generate a CSRF token to prevent CSRF attacks
 */
session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}
//require_once (__DIR__ . '../../src/models/Musers.php');
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Musers.php');
?>

<?php function drawHeader($db)
{ ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tickets website</title>
        <link href="../styles/common.css" rel="stylesheet">
        <link href="../styles/search.css" rel="stylesheet">
        <link href="../styles/ticket.css" rel="stylesheet">
        <link href="../styles/chat.css" rel="stylesheet">
        <link href="../styles/responsive.css" rel="stylesheet">
	    <script src="../scripts/chat.js"></script>
        <script src="../scripts/status.js" defer></script>
        <script src="../scripts/ticket.js" defer></script>
    </head>

    <body>
        <div class="page-container">
        <header>
        <?php drawNavbar($db); ?>
        </header>
    <main>
<?php } ?>


<?php function drawNavbar($db)
{ 
    $loggedIn = isset($_SESSION['email']); // check if the id key is set in the $_SESSION variable
 ?>

    <nav class="nav shadow-nohov">
        <div class="nav" id="nav-left">
            <a href="index.php"><span>TicketEase</span></a>
        </div>

        <div class="topnav" id="nav-right">
        <?php if ($loggedIn): ?>
        <!-- add links for logged in users here -->
        <a href='tickets.php'>TICKETS</a>
        <a href="#">CONTACT</a>
        <a href="faq.php">FAQ</a>
        <a href="profile.php">PROFILE</a>

        <?php    
        $email = $_SESSION['email'];
        $current_user = User::getUserByEmail($db, $email);
        if($current_user->getIsAdmin()) { ?>
            <a href="admin_management.php">MANAGEMENT</a>
        <?php
        } ?>
        
        <a href="../../src/controllers/logout.php">LOGOUT</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">☰</a>
        <?php else: ?>
        <!-- add links for non-logged in users here -->
        <a href="faq.php">FAQ</a>
        <a href="#">CONTACT</a>
        <a href="register.php">SIGN UP</a>
        <a href="login.php">LOGIN</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">☰</a>
        <?php endif; ?>
        </div>

    </nav>
<?php } ?>


<script>
    function myFunction() {
    var x = document.getElementById("nav-right");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
    }
</script>

<?php function drawFooter()
{ ?>
</main>
<footer id="app-footer">
    <section class="copyright">
        <p>&copy;TicketEase 2023</p>
    </section>
</footer>
</div>
</body>

</html>
<?php } ?>