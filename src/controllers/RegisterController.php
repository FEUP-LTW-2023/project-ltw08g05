<?php
require_once('../../src/models/Musers.php');
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Content-Type-Options: nosniff");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF 
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
      die('CSRF verification failed!');
    }
    RegisterController::register();
}

class RegisterController {

    public static function register() {
        session_start();
        if ($_SESSION['csrf'] !== $_POST['csrf']) {
          $_SESSION['error_message'] = 'Invalid form submission';
          header('Location: /public/views/register.php');
          exit();
        }
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password_confirm'];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $zip_code = $_POST['zip_code'];
        $bio = $_POST['bio'];
        $isAgent = isset($_POST['is_agent']) ? 1 : 0;
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

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
        /**
         * Passwords should match
         */
        if ($password !== $passwordConfirm) {
          $_SESSION['error_message'] = 'Passwords do not match';
          header("Location: /public/views/register.php");
          exit();
        }
        
        /**
        * Email should be unique
        */
        if(User::userExists($email)) {
          $_SESSION['error_message'] = 'User with this email already exists. Choose a different email.';
          header("Location: /public/views/register.php");
          exit();
        }
      
        // Hash the password before inserting it into the database
        // $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $db = new PDO("sqlite:../../database/database.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('INSERT INTO User (email, password, first_name, last_name, username, address, country, city, zip_code, bio, is_agent, is_admin) 
                              VALUES (:email, :password, :first_name, :last_name, :username, :address, :country, :city, :zip_code, :bio, :isAgent, :isAdmin)');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':country', $country);
        if(isset($city)) {
          $stmt->bindParam(':city', $city);
        } else {
          $stmt->bindValue(':city', null, PDO::PARAM_NULL);
        }
        if(isset($zip_code)) {
          $stmt->bindParam(':zip_code', $zip_code);
        } else {
          $stmt->bindValue(':zip_code', null, PDO::PARAM_NULL);
        }
        if (isset($bio)) {
          $stmt->bindParam(':bio', $bio);
        } else {
          $stmt->bindValue(':bio', null, PDO::PARAM_NULL);
        }
        $stmt->bindParam(':isAgent', $isAgent, PDO::PARAM_BOOL);
        $stmt->bindParam(':isAdmin', $isAdmin, PDO::PARAM_BOOL);
        $stmt->execute();
        $success = $stmt->rowCount();
        
        if($success) {
          /**
          * session_regenerate_id()
          * for security purposes we're generating a new random session id.
          * Using a random id as the session identifier can make it harder 
          * for attackers to guess or intercept the session ID.
          */
          session_regenerate_id();
          $_SESSION['email'] = $email;
          $_SESSION['justRegistered'] = "Welcome, new user!";
          $_SESSION['error_message'] = "";
          header("Location: /public/views/index.php");
        }
      }
      
      /**
       * Utility function to show all records from the database
       * Used only for debugging purposes during production
       */
      public static function showRecordsFromDatabase(){
        $db = new PDO('sqlite:../../database/database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('SELECT * FROM User');
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            echo $row['email'] . ' ' . $row['password'] . ' ' . $row['first_name'] . $row['last_name'] . $row['username'] . $row['address'] . $row['country']. '<br>';
        }
    }
      
}