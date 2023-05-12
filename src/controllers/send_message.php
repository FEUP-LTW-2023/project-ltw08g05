
<?php       // NOTE: This file is not used in the current version of the application
/*
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');

// Check if the required parameters are present
if (!isset($_POST['user_id']) || !isset($_POST['ticket_id'])  || !isset($_POST['message'])) {
    http_response_code(400);
    echo 'Error: missing required parameters';
    exit();
}

// Create a new Message object with the provided data
$user_id = intval($_POST['user_id']);
$ticket_id = intval($_POST['ticket_id']);
$message = $_POST['message'];

$new_message = new Message(null, $user_id, $ticket_id, $message, null);

// Save the message to the database
$db = getDatabaseConnection();
$new_message->sendMessage($db);

// Return a success message
echo 'Message sent successfully';
*/
?>