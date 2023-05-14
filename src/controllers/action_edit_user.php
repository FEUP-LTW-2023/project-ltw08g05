<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get new data
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $content = $_POST['content'];

    $db = getDatabaseConnection();

    $user = User::getUserByEmail($db, $email);
    $ticket = Ticket::getTicket($db, $id);
    if ($ticket) {
        $ticket->title = $_POST['title'];
        $ticket->content = $_POST['content'];
        $ticket->save($db);
    } 

    header("Location: ../../public/views/ticket.php?id=" . $_POST['id']);
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>