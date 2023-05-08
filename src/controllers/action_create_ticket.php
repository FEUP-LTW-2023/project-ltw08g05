<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';
require_once '../models/Mticket.php';
require_once '../models/Mdep.php';

session_start();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get new data
    $title = $_POST['title'];
    $departmentId = intval($_POST['department']);
    $content = $_POST['content'];
    $current_user = User::getUserByEmail($db, $_SESSION['email']);
    $userID = $current_user->getUserId();

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
