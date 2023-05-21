<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';

session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get data
    $id = $_POST['id'];
    $assigneeEmail = $_POST['assignee'];

    $db = getDatabaseConnection();
    $ticket = Ticket::getTicket($db, $id);
    $agent = User::getUserByEmail($db, $assigneeEmail);

    if($id == null){
        $_SESSION['error_message'] = 'Ticket not found';
        header('Location: /public/views/assign_ticket.php?id=' . $id);
        exit();
    }
    if($assigneeEmail == null){
        $_SESSION['error_message'] = 'Please select an Agent';
        header('Location: /public/views/assign_ticket.php?id=' . $id);
        exit();
    }
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