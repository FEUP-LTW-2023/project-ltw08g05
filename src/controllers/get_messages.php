<?php        
declare(strict_types=1);

require_once(__DIR__ . '/../models/Mmessage.php');
require_once(__DIR__ . '/../../database/connection.php');

session_start();
$ticket_id = $_GET['ticket_id'];

//var_dump($ticket_id);
// Fetch messages for the given ticket ID
$db = getDatabaseConnection();
$messages = Message::getMessages($db, intVal($ticket_id));

// Return the messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);

?>