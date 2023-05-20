<?php
header("Content-Security-Policy: default-src 'self'");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Content-Type-Options: nosniff");

/**
 * start a session and generate a CSRF token to prevent CSRF attacks
 */
session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify the CSRF token
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF token verification failed!');
    }
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    /**
     * XSS Prevention
     * If the input contains unexpected characters reject it
     */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format");
        $_SESSION['error_message'] = "Invalid email format";
        header("Location: /public/views/profile.php");
        exit();
    }
    
    $db = getDatabaseConnection();
    if ($db == null){
        error_log("$db is null");
        throw new Exception('Database not initialized');
        die("$db is null");
    }
    
    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = 'Passwords do not match';
        header("Location: /public/views/profile.php");
        exit();
    }

    // Check if the password is too weak
    if (strlen($new_password) < 8) {
        $_SESSION['error_message'] = 'Password must be at least 8 characters long';
        header("Location: /public/views/profile.php");
        exit();
    }

    // Retrieve the user from the database
    $user = User::getUserByEmail($db, $email);
    if(!$user){
        echo "Oops, we've got a problem with the database connection:";
        die("User not found.");
    }
    

    if ($user && password_verify($current_password, $user->password)) {
        // Hash the new password before saving
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $user->savePassword($db, $hashed_password);
    }
    else {
        // Incorrect current password
        $_SESSION['error_message'] = 'Incorrect current password';
        header("Location: /public/views/profile.php");
        exit();
    }

    // Redirect to the profile page
    header("Location: /public/views/profile.php");
}

?>
