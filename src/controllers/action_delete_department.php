<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mdep.php');

    $db = getDatabaseConnection();
    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $departmentId = $_GET['id'];

    error_log("departmentId: ".$departmentId);

    // To Do:
    // Change ticket department to NULL (when ticket->departmentId==departmentId)

    Department::deleteDepartment($db, $departmentId);

    header("Location: ../../public/views/admin_management.php");
    exit();
?>