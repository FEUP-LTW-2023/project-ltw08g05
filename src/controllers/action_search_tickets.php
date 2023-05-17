<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../../database/connection.php');
  require_once '../models/Mticket.php';

  session_start();
  $db = getDatabaseConnection();

  $tickets = Ticket::searchTickets($db, $_GET['search'], 8);

  echo json_encode($tickets);
?>