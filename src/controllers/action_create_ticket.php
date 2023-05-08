<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Mticket.php';
require_once '../models/MDep.php';

$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get new data
    $title = $_POST['title'];
    $departmentId = intval($_POST['department']);
    $content = $_POST['content'];

    $ticket = new Ticket(
        null, 
        null, 
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
