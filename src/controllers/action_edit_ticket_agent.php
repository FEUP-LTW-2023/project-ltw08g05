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
        die('CSRF verification failed!');
    }

    // Get new data
    $email = $_POST['email'];
    $id = intVal($_POST['id']);
    $departmentId = intval($_POST['department']);

    // Validate email
    if ($email === false) {
        error_log("Invalid email format");
        $_SESSION['error_message'] = "Invalid email format";
        header("Location: /public/views/ticket.php?id=".$id);
        exit();
    }

    // Validate ID
    if ($id === false || $id <= 0) {
        error_log("Invalid ID");
        $_SESSION['error_message'] = "Invalid ID";
        header("Location: /public/views/ticket.php?id=".$id);
        exit();
    }

    // Validate department ID
    if ($departmentId === false || $departmentId <= 0) {
        error_log("Invalid department ID");
        $_SESSION['error_message'] = "Invalid department ID";
        header("Location: /public/views/ticket.php?id=".$id);
        exit();
    }


    $db = getDatabaseConnection();
    if($db == null){
        die('Database error');
    }
    $current_user = User::getUserByEmail($db, $email);
    if($current_user == null){
        die('User not found');
    }
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
        if($ticket->departmentID != $departmentId) {
            Ticket::saveHistory($db, $id, $current_user->getUserId(), 'departmentID', $ticket->departmentID, $departmentId);
        }
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