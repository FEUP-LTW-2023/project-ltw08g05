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
    
    </head>

    <body style="overflow: scroll;">
    <?php drawNavbar(); ?>
    <main>
<?php } ?>


<?php function drawNavbar()
{ 
if (isset($_SESSION['id'])) {
    $db = getDatabaseConnection();
    // get user statement here
} ?>
    
    <nav class="">
        <div class="nav" id="nav-left">
            <button><span onclick="window.location.href = 'index.php';" class="">TicketEase</span></button>
        </div>

        <ul class="nav" id="nav-right">
            <!-- session condition to verify if user is logged in or not -->
            <li><a class="" id="" href='tickets.php'>TICKETS</a></li>
            <li><a class="" id="" href="faq.php">FAQ</a></li>
            <li><a class="" id="" href="#">CONTACT</a></li>
            <li><a class="" id="" onclick="">Login</a></li>
            <li><a class="" id="nav-right-signup" onclick="">Sign-Up</a></li>
            
            <section id="dropdown-content">
                <form action="" method="">
                    <button type="submit" class="">
                        Logout
                    </button>
                </form>
            </section>
        </ul>
    </nav>
    
<?php } ?>

<?php function drawFooter()
{ ?>
</main>
<footer id="app_footer">
    <section class="copyright">
        <p>&copy;TicketEase 2023</p>
    </section>
</footer>
</body>

</html>
<?php } ?>