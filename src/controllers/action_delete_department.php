<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mdep.php');
    require_once('../../src/models/Mticket.php');

    $db = getDatabaseConnection();
    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $departmentId = $_GET['id'];

    $departmentTickets = Ticket::getDepartmentTickets($db, $departmentId);

    foreach($departmentTickets as $departmentTicket) {
        $departmentTicket->departmentID = null;
        $departmentTicket->save($db);
    }

    Department::deleteDepartment($db, $departmentId);

    header("Location: ../../public/views/admin_management.php");
    exit();
?>