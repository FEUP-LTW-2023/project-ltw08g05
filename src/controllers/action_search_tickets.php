<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../../database/connection.php');
  require_once '../models/Mticket.php';
  require_once '../models/Musers.php';

  session_start();
  $db = getDatabaseConnection();
  $user = $_SESSION['email'];
  $current_user = User::getUserByEmail($db, $user);

  if($current_user->getIsAgent()) {
    $tickets = Ticket::searchTickets($db, $_GET['search']);
  } else {
    $tickets = Ticket::searchUserTickets($db, $_GET['search'], $current_user->getUserId());
  }

  echo json_encode($tickets);
?>