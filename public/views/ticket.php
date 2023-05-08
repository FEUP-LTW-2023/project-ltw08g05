<?php

require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../../src/models/Musers.php');
require_once('../templates/Tcommon.php');
require_once('../templates/Ttickets.php');

$db = getDatabaseConnection();
if ($db == null){
    throw new Exception('Database not initialized');
    ?> <p>$db is null</p> <?php
}

$ticket = Ticket::getTicket($db, intval($_GET['id']));

drawHeader();
drawTicket($ticket);
drawFooter();
?>