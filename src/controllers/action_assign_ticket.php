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
    $id = $_POST['id'];
    $assigneeEmail = $_POST['assignee'];

    $db = getDatabaseConnection();
    $ticket = Ticket::getTicket($db, $id);
    $agent = User::getUserByEmail($db, $assigneeEmail);

    if ($ticket) {
        $ticket->agentAssignedID = $agent->userID;
        $ticket->saveAssign($db);
    } 

    header("Location: ../../public/views/ticket.php?id=" . $_POST['id']);
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>