<?php
session_start();


require_once('../models/Musers.php');

$email = $_POST['email'];
$password = $_POST['password'];

if (User::userExists($email, $password)) {
    $_SESSION['email'] = $email;
    header('Location: /index.php');
    exit();
} else {
    $_SESSION['email'] = $email;
    $_SESSION['error_message'] = 'Email ' . $email . ' does not exist in the db';
    header('Location: /src/controllers/login.php');
    exit();
}
?>
