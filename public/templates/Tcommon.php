<?php
declare(strict_types=1); 
?>

<?php
//require_once (__DIR__ . '../../src/models/Musers.php');
require_once(__DIR__ . '/../../database/connection.php');
?>

<?php function drawHeader()
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
        <?php drawNavbar(); ?>
        </header>
    <main>
<?php } ?>


<?php function drawNavbar()
{ 
    session_start(); // start the session to access the $_SESSION variable
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