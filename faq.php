<?php
/**
 * It is generally recommended to include the code for database connection 
 * at the top of the page, before any HTML output is sent.
*/

// initialize database connection
require_once('database/connection.php');
require_once('database/faq.php');
require_once('templates/common.php');
$db = getDatabaseConnection();
?>

<?php
output_header();
?>
    <h1>FAQ</h1>
    
    <?php
    $tickets = getAllTickets($db);
    ?>

<?php
output_footer();
?>