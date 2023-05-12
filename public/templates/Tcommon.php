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
        <link href="../styles/profile.css" rel="stylesheet">
        <link href="../styles/chat.css" rel="stylesheet">
	    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	    <script src="../scripts/chat.js"></script>
    </head>

    <body>
    <?php drawNavbar(); ?>
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

        <ul class="nav" id="nav-right">
            <?php if ($loggedIn): ?>
            <!-- add links for logged in users here -->
            <li><a href='tickets.php'>TICKETS</a></li>
            <li><a href="#">CONTACT</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="profile.php">PROFILE</a></li>
            <li><a href="../../src/controllers/logout.php">LOGOUT</a></li>
            <?php else: ?>
            <!-- add links for non-logged in users here -->
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="#">CONTACT</a></li>
            <li><a href="register.php">SIGN UP</a></li>
            <li><a href="login.php">LOGIN</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
<?php } ?>

<?php function drawFooter()
{ ?>
</main>
<footer id="app-footer">
    <section class="copyright">
        <p>&copy;TicketEase 2023</p>
    </section>
</footer>
</body>

</html>
<?php } ?>