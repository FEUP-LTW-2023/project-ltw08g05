<?php
/**
 * It is generally recommended to include the code for database connection 
 * at the top of the page, before any HTML output is sent.
*/

// require_once('../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../templates/Tcommon.php');
$db = new PDO('sqlite:../../database/database.db');
if ($db == null){
    throw new Exception('Database not initialized');
    ?> <p>$db is null</p> <?php
  }
$tickets = getAllTickets($db);
output_header();

?>
    <h1>TICKET page</h1>
    <p><?php echo $tickets ?></p>
<?php

output_ticket_list($tickets);    
output_footer();
?>