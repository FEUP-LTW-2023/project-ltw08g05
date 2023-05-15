<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get data
    $id = $_POST['id'];
    $assigneeEmail = $_POST['assignee'];
    $statusValue = $_POST['status'];

    $db = getDatabaseConnection();
    $ticket = Ticket::getTicket($db, $id);
    $agent = User::getUserByEmail($db, $assigneeEmail);

    if ($ticket) {
        $ticket->agentAssignedID = $agent->userID;
        $ticket->status = $statusValue;
        $ticket->saveAssign($db);
    } 

    header("Location: ../../public/views/ticket.php?id=" . $_POST['id']);
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>