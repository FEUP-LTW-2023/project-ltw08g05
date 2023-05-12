<?php
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';

// Get data
$agent_email = $_POST['user_email'];
$ticket_id = intval($_POST['id']);
$message = $_POST['message'];

// Save the message to the database
$db = getDatabaseConnection();
$ticket = Ticket::getTicket($db, $ticket_id);
$agent = User::getUserByEmail($db, $agent_email);

if ($ticket) {
    $ticket->response = $message;
    $ticket->saveResponse($db);
} 
header("Location: ../../public/views/ticket.php?id=" . $_POST['id']);
exit();



