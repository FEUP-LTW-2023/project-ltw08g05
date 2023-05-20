<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/Musers.php';
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

    // Get new data
    $email = $_POST['email'];
    $id = intVal($_POST['id']);
    $departmentId = intval($_POST['department']);
    $title = $_POST['title'];
    $content = $_POST['content'];

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

    // Validate title
    if ($title === null || $title === "") {
        error_log("Title is required");
        $_SESSION['error_message'] = "Title is required";
        header("Location: /public/views/ticket.php?id=".$id);
        exit();
    }

    // Validate content
    if ($content === null || $content === "") {
        error_log("Content is required");
        $_SESSION['error_message'] = "Content is required";
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
