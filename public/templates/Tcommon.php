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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-Xb+PnNbz9s3q4SK4twHv/UV+Zie70C/2fS2BtPv2IyXYoX9Xli38WswiYZGWMwAj1DOKnwnpJFNkHlDKm13uOg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    
    <nav class="nav shadow-nohov">
        <div class="nav" id="nav-left">
            <a href="index.php"><span>TicketEase</span></a>
        </div>

        <ul class="nav" id="nav-right">
            <!-- add here session condition to verify if user is logged in or not -->
            <li><a href='tickets.php'>TICKETS</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">CONTACT</a></li>
            <li><a href="#">Login</a></li>
            
            <!--  Logout dropdown
            <section id="dropdown">
                <form action="" method="">
                    <button type="submit" class="">
                        Logout
                    </button>
                </form>
            </section>-->
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