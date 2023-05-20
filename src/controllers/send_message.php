<?php       
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');

session_start();
// Create a new Message object with the provided data
$user_id = $_GET['user_id'];
$ticket_id = $_GET['ticket_id'];
$message = $_GET['message'];

//echo(print_r($message));

$new_message = new Message(null, intval($user_id), intVal($ticket_id), $message, null);

// Save the message on the database
$db = getDatabaseConnection();
$new_message->sendMessage($db);

// Return a success message
echo 'Message sent successfully';

?>