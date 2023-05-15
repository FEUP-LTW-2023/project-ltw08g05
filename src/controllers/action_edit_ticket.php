<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $db = getDatabaseConnection();
    // get new data
    $id = $_POST['id'];
    $current_user = User::getUserByEmail($db, $_SESSION['email']);
   
    $departmentId = intval($_POST['department']);
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $ticket = Ticket::getTicket($db, $id);
    if ($ticket && $current_user && $current_user->getIsAgent()) {
        $ticket->departmentID = $departmentId;
        $ticket->saveDep($db);
    }
    else if ($ticket) {
        $ticket->title = $_POST['title'];
        $ticket->content = $_POST['content'];
        $ticket->save($db);
        $ticket->departmentID = $departmentId;
    } 
   
    header("Location: ../../public/views/ticket.php?id=" . $_POST['id']);
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
