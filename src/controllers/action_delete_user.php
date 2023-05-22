<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Musers.php');
    require_once('../../src/models/Mticket.php');
    require_once('../../src/models/Mdep.php');

    $db = getDatabaseConnection();
    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $userId = $_GET['id'];

    $userTickets = Ticket::getUserTickets($db, (int) $userId);

    foreach($userTickets as $userTicket) {
       Ticket::deleteTicket($db,  $userTicket->id);
    }

    $userDepartments = Department::getDepartmentsByAgent($db, (int) $userId);

    if($userDepartments !== null) {
        foreach($userDepartments as $userDepartment) {
            $userDepartment->userID = null;
        }
    }

    $agentTickets = Ticket::getTicketsByAgent($db, $userId);

    if($agentTickets !== null) {
        foreach($agentTickets as $agentTicket) {
            $agentTicket->agentAssignedID = null;
            $agentTicket->status = "Open";
            $agentTicket->saveStatus($db);
            $agentTicket->saveAssign($db);
        }
    }

    User::deleteUser($db, $userId);

    header("Location: ../../public/views/admin_management.php");
    exit();
?>