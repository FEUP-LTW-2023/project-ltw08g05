<?php
    require_once(__DIR__ . '/../../database/connection.php');
    require_once '../models/Mticket.php';
    require_once '../models/Musers.php';
    
    session_start();
    if (!isset($_SESSION['csrf'])) {
      $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
?>