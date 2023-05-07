<?php
require_once('../../src/models/Musers.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    LoginController::login();
}

class LoginController
{
    public function showLoginForm()
    {
        session_start();
        include '../public/views/login.php';
    }

    public static function login()
    {
        
        session_start();
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (User::userExists($email, $password)) {
            $_SESSION['email'] = $email;
            header('Location: /index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'does not exist in the db';
            $_SESSION['email'] = $email;
            header('Location: /public/views/login.php');
            exit();
        }
    }
    public static function showRecordsFromDatabase(){
        $db = new PDO('sqlite:../../database/database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('SELECT * FROM User');
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            echo $row['email'] . ' ' . $row['password'] . '<br>';
        }
    }
}
