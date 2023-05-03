<?php
/**
 * It is generally recommended to include the code for database connection 
 * at the top of the page, before any HTML output is sent.
*/
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../../src/models/Musers.php');
require_once('../templates/Tcommon.php');
require_once('../templates/Ttickets.php');

$db = getDatabaseConnection();
if ($db == null){
    throw new Exception('Database not initialized');
    ?> <p>$db is null</p> <?php
}

$tickets = Ticket::getAllTickets($db);

// for when login is implemented -- tickets of a client
// $tickets = User::getUserTickets($db, intval($_SESSION['id']));
// $user = User::getUser($db, $_SESSION['id']);

drawHeader();

?>
    <h1>TICKET page</h1>
    <p><?php echo $tickets ?></p>
<?php

output_ticket_list($tickets);    
drawFooter();
?>