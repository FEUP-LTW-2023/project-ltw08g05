<?php
/**
 * It is generally recommended to include the code for database connection 
 * at the top of the page, before any HTML output is sent.
*/

// initialize database connection
require_once('../database/connection.php');
require_once('../database/ticket.php');
require_once('../templates/common.php');
$db = getDatabaseConnection();
$tickets = getAllTickets($db);
output_header();

?>
    <h1>TICKET page</h1>
<?php
output_ticket_list($tickets);    
output_footer();
?>