<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get new data
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];
  $email = $_POST['email'];
  
  $db = getDatabaseConnection();

  // Check if passwords match
  if ($new_password !== $confirm_password) {
    $_SESSION['error_message'] = 'Passwords do not match';
    header("Location: /public/views/edit_profile.php");
    exit();
  }

  // Retrieve the user from the database
  $user = User::getUserByEmail($db, $email);
  if(!$user){
    echo "Oops, we've got a problem with the database connection:";
    die("User not found.");
  }
  error_log("action_change_password.php: " . print_r($user, true));
  error_log("OLD HASH: " . $user->password);


  error_log("current_password: ". $current_password);
  if ($user && password_verify($current_password, $user->password)) {
    // Hash the new password before saving
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    error_log("New Hash: " . $hashed_password);

    $password = 'rhengh79';

    if (password_verify($password, $hashed_password)) {
        echo "Password matches the hash.";
        error_log("Password matches the hash.");
    } else {
        echo "Password does not match the hash.";
        error_log("Password does not match the hash.");
    }

    $user->savePassword($db, $hashed_password);
  }
  else {
    // Incorrect current password
    $_SESSION['error_message'] = 'Incorrect current password';
    header("Location: /public/views/change_password.php");
    exit();
  }

    // Redirect to the profile page
    header("Location: /public/views/profile.php");
}

?>
