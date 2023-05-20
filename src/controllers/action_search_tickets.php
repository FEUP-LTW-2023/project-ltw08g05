<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/../../database/connection.php');
  require_once '../models/Mticket.php';
  require_once '../models/Musers.php';
  header("Content-Security-Policy: default-src 'self'");
  header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
  header("X-Content-Type-Options: nosniff");

  session_start();
  if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
  
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