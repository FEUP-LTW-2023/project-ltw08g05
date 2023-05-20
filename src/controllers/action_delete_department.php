<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mdep.php');

    header("Location: ../../public/views/admin_management.php");
    exit();
?>