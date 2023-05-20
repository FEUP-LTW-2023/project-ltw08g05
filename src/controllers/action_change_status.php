<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';

session_start();
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    // Verify CSRF 
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die("Invalid CSRF token.");
    }
    // get data
    $id = intVal($_POST['id']);
    $statusValue = $_POST['status'];
    
    $db = getDatabaseConnection();
    if($db == null){
        die('Database error');
    }
    $ticket = Ticket::getTicket($db, $id);
    //echo(print_r($_POST));
    if ($ticket) {
        $ticket->status = $statusValue;
        $ticket->saveStatus($db);
        //echo(print_r($ticket));
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