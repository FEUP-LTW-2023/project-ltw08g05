<?php
require_once('../../src/models/Musers.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    RegisterController::register();
}

class RegisterController {
    public static function register() {
        session_start();
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        
        /**
         * Email should be unique
         */
        if(User::userExists($email)) {
            $_SESSION['error_message'] = 'User with this email already exists. choose a different email.';
            header("Location: /public/views/register.php");
            exit();
        }

        // Hash the password before inserting it into the database
        // $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $db = new PDO("sqlite:../../database/database.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('INSERT INTO User (email, name, password) VALUES (:email, :name, :password)');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
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
