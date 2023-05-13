<?php
require_once('../../src/models/Musers.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    RegisterController::register();
}

class RegisterController {
    public static function register() {
        session_start();
        $email = $_POST['email'];
        $password = $_POST['password'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $username = $_POST['username'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $zipCode = $_POST['zipCode'];
        $bio = $_POST['bio'];
        $isAgent = isset($_POST['is_agent']) ? 1 : 0;
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;
      
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
                              VALUES (:email, :password, :firstName, :lastName, :username, :address, :country, :city, :zipCode, :bio, :isAgent, :isAdmin)');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':country', $country);
        if(isset($city)) {
          $stmt->bindParam(':city', $city);
        } else {
          $stmt->bindValue(':city', null, PDO::PARAM_NULL);
        }
        if(isset($zipCode)) {
          $stmt->bindParam(':zipCode', $zipCode);
        } else {
          $stmt->bindValue(':zipCode', null, PDO::PARAM_NULL);
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
          $_SESSION['error_message'] = "No errors";
          header("Location: /public/views/index.php");
        }
      }
      
}