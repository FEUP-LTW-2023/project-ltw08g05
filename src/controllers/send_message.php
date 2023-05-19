<?php       
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');


// Create a new Message object with the provided data
$user_id = intval($_GET['user_id']);
$ticket_id = intval($_GET['ticket_id']);
$message = $_GET['message'];

echo(print_r($ticket_id));

$new_message = new Message(null, $user_id, $ticket_id, $message, null);

// Save the message on the database
$db = getDatabaseConnection();
$new_message->sendMessage($db);

// Return a success message
echo 'Message sent successfully';

?>