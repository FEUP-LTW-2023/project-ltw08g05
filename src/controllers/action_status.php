<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';
header("Content-Security-Policy: default-src 'self'");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Content-Type-Options: nosniff");
session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF 
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF verification failed!');
    }
    
    // get data
    //var_dump($_POST);
    $id = intVal($_POST['id']);
    $statusValue = $_POST['status'];
    
    $db = getDatabaseConnection();
    $ticket = Ticket::getTicket($db, $id);

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