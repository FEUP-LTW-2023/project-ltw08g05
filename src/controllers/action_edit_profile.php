<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';

session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF 
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF verification failed!');
    }
    // get new data
    $email = $_POST['email'];
    $new_email = $_POST['new_email'];
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $bio = $_POST['bio'];

    /**
     * XSS Prevention
     * If the input contains unexpected characters reject it
     */
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) { 
        error_log("Invalid username format");
        $_SESSION['error_message'] = "Invalid username format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
        error_log("Invalid first name format");
        $_SESSION['error_message'] = "Invalid first name format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
        error_log("Invalid last name format");
        $_SESSION['error_message'] = "Invalid last name format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format");
        $_SESSION['error_message'] = "Invalid email format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid new email format");
        $_SESSION['error_message'] = "Invalid new email format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!preg_match('/^[a-zA-Z0-9_\s]+$/', $address)) {
        error_log("Invalid address format");
        $_SESSION['error_message'] = "Invalid address format";
        header("Location: /public/views/profile.php");
        exit();
    }
    /**
     * country, city, zip_code, bio can be empty
     */ 
    if (!empty($country) && !preg_match('/^[a-zA-Z\s]+$/', $country)) {
        error_log("Invalid country format");
        $_SESSION['error_message'] = "Invalid country format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!empty($city) && !preg_match('/^[a-zA-Z\s]+$/', $city)) {
        error_log("Invalid city format");
        $_SESSION['error_message'] = "Invalid city format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!empty($zip_code) && !preg_match('/^[0-9-]+$/', $zip_code)) {
        error_log("Invalid zip code format");
        $_SESSION['error_message'] = "Invalid zip code format";
        header("Location: /public/views/profile.php");
        exit();
    }
    if (!empty($bio) && !preg_match('/^[\pL\s\d.,!?"\']+$/', $bio)) {
        error_log("Invalid bio format");
        $_SESSION['error_message'] = "Invalid bio format";
        header("Location: /public/views/profile.php");
        exit();
    }
    

    $db = getDatabaseConnection();

    $user = User::getUserByEmail($db, $new_email);
    error_log("here:");
    error_log($email);
    error_log($new_email);
    if($email === $new_email){
        
    }
    else if($user){
        ?> <p> <?php echo "A user with this email already exists! Please, choose another one."; ?> </p> <br> 
        <?php $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $user = User::getUserByEmail($db, $email);
    if($user){
        $user->email = $new_email;
        $user->username = $username;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->address = $address;
        $user->country = $country;
        $user->city = $city;
        $user->zip_code = $zip_code;
        $user->bio = $bio;
        $user->save($db);
    } else {
        echo "Oops, we've got a problem with database connection:";
        die("User not found.");
    }
    
    header("Location: ../../public/views/profile.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>