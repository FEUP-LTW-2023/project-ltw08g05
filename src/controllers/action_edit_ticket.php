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

    // Check if user is agent and he's not the author of the ticket -- in this case he can only change the department
    if ($ticket && $current_user && $current_user->getIsAgent() && $current_user->userID != $ticket->userID) {
        if($departmentId == null){
            $_SESSION['error_message'] = 'Please select a department';
            header('Location: /public/views/edit_ticket.php?id=' . $id . '');
            exit();
        }

        // Record changes in TicketHistory
        if($ticket->departmentID != $departmentId) {
            Ticket::saveHistory($db, $id, $current_user->getUserId(), 'departmentID', $ticket->departmentID, $departmentId);
        }
        
        $ticket->departmentID = $departmentId;
        $ticket->saveDep($db);
    }
    // other users/agents who are the authors -- can change everything 
    else if ($ticket) {
        // Record changes in TicketHistory
        if($ticket->title != $title) {
            Ticket::saveHistory($db, $id, $current_user->getUserId(), 'title', $ticket->title, $title);
        }
        if($ticket->content != $content) {
            Ticket::saveHistory($db, $id, $current_user->getUserId(), 'content', $ticket->content, $content);
        }
        if($ticket->departmentID != $departmentId) {
            Ticket::saveHistory($db, $id, $current_user->getUserId(), 'departmentID', $ticket->departmentID, $departmentId);
        }

        $ticket->title = $_POST['title'];
        $ticket->content = $_POST['content'];
        $ticket->departmentID = $departmentId;
        $ticket->save($db);
    } 
   
    header("Location: ../../public/views/ticket.php?id=" . $_POST['id']);
    exit();
} else {
    header("Location: index.php");
    exit();
}

?>
