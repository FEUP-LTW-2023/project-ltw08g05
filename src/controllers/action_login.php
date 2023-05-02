<?php
session_start();

require_once('../../database/connection.php');
require_once('../models/Musers.php');

$email = $_POST['email'];
$password = $_POST['password'];

if (userExists($email, $password)) {
    $_SESSION['email'] = $email;
    header('Location: /public/views/index.php');
    exit();
} else {
    $_SESSION['email'] = $email;
    $_SESSION['error_message'] = 'Email ' . $email . ' does not exist in the db';
    header('Location: /src/controllers/login.php');
    exit();
}
?>
