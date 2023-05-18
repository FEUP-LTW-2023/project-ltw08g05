<?php

require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../../src/models/Musers.php');
require_once('../../src/models/Mdep.php');
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

$ticket = Ticket::getTicket($db, intval($_GET['id']));
$dep = Department::getDepartment($db, $ticket->departmentID);

drawHeader($db);
drawTicket($ticket, $current_user, $dep);

//echo(print_r($ticket));

drawFooter();
?>