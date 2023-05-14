<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        error_log($user->email);
        error_log($new_email);
        error_log($user->username);
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