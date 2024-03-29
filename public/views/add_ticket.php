<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../../src/models/Musers.php');
require_once('../../src/models/Mdep.php');
require_once('../templates/Tcommon.php'); // session_start() is declared here
require_once('../templates/Ttickets.php');

// connect to database
try {
    $db = getDatabaseConnection();
} catch (PDOException $e) {
    echo "Oops, we've got a problem related to database connection:";
    die("Error connecting to database: " . $e->getMessage());
}

$deps = Department::getAllDepartments($db);

drawHeader($db);
drawAddTicket($deps);
drawFooter();

?> 

