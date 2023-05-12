<?php /*        // NOTE: This file is not used in the current version of the application
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');

// Check if the required parameters are present
if (!isset($_POST['ticket_id'])) {
    http_response_code(400);
    echo 'Error: ticket_id parameter is missing';
    exit();
}

$ticket_id = intval($_POST['ticket_id']);

// Fetch messages for the given ticket ID
$db = getDatabaseConnection();
$messages = Message::getMessages($db, $ticket_id);

// Return the messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);
*/
?>