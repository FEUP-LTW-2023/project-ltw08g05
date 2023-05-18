<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Musers.php');

    $db = getDatabaseConnection();
    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $userId = $_GET['id'];

    User::deleteUser($db, $userId);

    header("Location: ../../public/views/admin_management.php");
    exit();
?>