<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // get data
    //var_dump($_POST);
    $id = intVal($_POST['id']);
    $statusValue = $_POST['status'];
    
    $db = getDatabaseConnection();
    $ticket = Ticket::getTicket($db, $id);

    if ($ticket) {
        $ticket->status = $statusValue;
        $ticket->saveStatus($db);
        echo(print_r($ticket));
        echo 'Success';             // Add this line to indicate success
    } else {
        echo 'Ticket not found';    // Add this line to indicate if the ticket was not found
    }
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>