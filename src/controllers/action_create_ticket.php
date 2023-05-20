<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';
require_once '../models/Mticket.php';
require_once '../models/Mdep.php';
require_once '../models/Mmessage.php';
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Content-Type-Options: nosniff");
session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF 
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF verification failed!');
    }
    // get new data
    $title = $_POST['title'];
    $departmentId = intval($_POST['department']);
    $content = $_POST['content'];
    $current_user = User::getUserByEmail($db, $_SESSION['email']);
    $userID = $current_user->getUserId();

    if($departmentId == null){
        $_SESSION['error_message'] = 'Please select a department';
        header('Location: /public/views/add_ticket.php');
        exit();
    }
    if($title == null){
        $_SESSION['error_message'] = 'Please enter a title';
        header('Location: /public/views/add_ticket.php');
        exit();
    }
    if($content == null){
        $_SESSION['error_message'] = 'Please enter a content';
        header('Location: /public/views/add_ticket.php');
        exit();
    }
    $ticket = new Ticket(
        null, 
        $userID, 
        $departmentId,
        null,
        $title, 
        $content,            
        'Open',
        date("Y-m-d"),
        null
    );
    $ticket->add($db);
    header("Location: ../../public/views/tickets.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
