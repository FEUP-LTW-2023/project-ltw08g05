<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $db = getDatabaseConnection();
    // get new data
    $id = intVal($_POST['id']);
    $current_user = User::getUserByEmail($db, $_SESSION['email']);
   
    $departmentId = intval($_POST['department']);
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $ticket = Ticket::getTicket($db, $id);
    if ($ticket && $current_user && $current_user->getIsAgent()) {
        if($departmentId == null){
            $_SESSION['error_message'] = 'Please select a department';
            header('Location: /public/views/edit_ticket.php?id=' . $id . '');
            exit();
        }
        $ticket->departmentID = $departmentId;
        $ticket->saveDep($db);
    }
    else if ($ticket) {
        
        if($departmentId == null){
            $_SESSION['error_message'] = 'Please select a department';
            header('Location: /public/views/edit_ticket.php?id=' . $id . '');
            exit();
        }
        if($title == null){
            $_SESSION['error_message'] = 'Please enter a title';
            header('Location: /public/views/edit_ticket.php?id=' . $id . '');
            exit();
        }
        if($content == null){
            $_SESSION['error_message'] = 'Please enter a content';
            header('Location: /public/views/edit_ticket.php?id=' . $id . '');
            exit();
        }
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
