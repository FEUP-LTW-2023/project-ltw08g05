<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../../src/models/Musers.php');
require_once('../../src/models/Mdep.php');
require_once('../templates/Tcommon.php'); // session_start() is declared here
require_once('../templates/Ttickets.php');

// check if id parameter is set
if (!isset($_GET['id'])) {
    die("Ticket ID not provided.");
}

// connect to database
try {
    $db = getDatabaseConnection();
} catch (PDOException $e) {
    echo "Oops, we've got a problem related to database connection:";
    die("Error connecting to database: " . $e->getMessage());
}

$id = $_GET['id'];
$ticket = Ticket::getTicket($db, intval($_GET['id']));
$current_user = User::getUserByEmail($db, $_SESSION['email']);
$deps = Department::getAllDepartments($db);

drawHeader($db);
drawEditTicket($ticket, $current_user, $deps);
drawFooter();

?> 