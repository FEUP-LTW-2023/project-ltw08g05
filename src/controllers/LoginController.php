<?php
require_once('../../src/models/Musers.php');
require_once('../../src/controllers/AccessLogController.php');
session_start();

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = generate_random_token();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify the CSRF token
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF token verification failed!');
    }
    LoginController::login();
}
/**
 * CSRF token generation
 */
function generate_random_token() {
    return bin2hex(openssl_random_pseudo_bytes(32));
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
    
        // Create database connection
        $db = new PDO('sqlite:../../database/database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        if (User::userPasswordMatch($db, $email, $password)) {
            $_SESSION['csrf'] = generate_random_token();
            $_SESSION['email'] = $email;
            AccessLogController::updateAccessLog($db, $email);  // Update access log if the user is logged in successfully
            header('Location: /index.php');
            unset($_SESSION['error_message']);
            exit();
        } else {
            $_SESSION['error_message'] = 'credentials do not match';
            header('Location: /src/controllers/logout.php');
            exit();
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
