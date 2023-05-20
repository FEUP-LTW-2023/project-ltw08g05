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

session_start();
$loggedIn = isset($_SESSION['email']);
$db = getDatabaseConnection();

if ($db == null){
    throw new Exception('Database not initialized');
    ?> <p>$db is null</p> <?php
}
$email = $_SESSION['email'];
$current_user = User::getUserByEmail($db, $email);

if ($current_user->getIsAgent()) {
    $tickets = Ticket::getAllTickets($db);
} else {
    $tickets = Ticket::getUserTickets($db, $current_user->getUserId());
}

drawHeader($db);

drawAllTickets($tickets, $current_user);   
//echo(print_r($current_user));
//var_dump($current_user);
//echo $current_user->isAgent; // Should output 0 or false
//var_dump($current_user->getIsAgent());

drawFooter();
?>