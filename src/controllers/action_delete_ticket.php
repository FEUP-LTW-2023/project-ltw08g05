<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mticket.php');

    $db = getDatabaseConnection();
    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $ticketId = $_GET['id'];

    Ticket::deleteTicket($db, $ticketId);

    header("Location: ../../public/views/admin_management.php");
    exit();
?>