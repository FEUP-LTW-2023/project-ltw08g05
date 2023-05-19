<?php        
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');


$ticket_id = intval($_GET['id']);

var_dump($ticket_id);
// Fetch messages for the given ticket ID
$db = getDatabaseConnection();
$messages = Message::getMessages($db, $ticket_id);

// Return the messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);

?>