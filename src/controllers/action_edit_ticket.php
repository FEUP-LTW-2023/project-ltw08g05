<?php
require_once '../models/Mticket.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get new data
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // pass new data to database
    
    updateTicket($id, $title, $content);

    header("Location: ../../public/views/tickets.php");

    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
