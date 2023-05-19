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
    
        // Create database connection
        $db = new PDO('sqlite:../../database/database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        if (User::userPasswordMatch($db, $email, $password)) {
            $_SESSION['email'] = $email;
            LoginController::updateAccessLog($db, $email);  // Update access log if the user is logged in successfully
            header('Location: /index.php');
            unset($_SESSION['error_message']);
            exit();
        } else {
            $_SESSION['error_message'] = 'credentials do not match';
            header('Location: /public/views/login.php');
            exit();
        }
    }


    /**
     * Update access log 
     * using global $_SERVER array 
     */
    public static function updateAccessLog($db, $email){
        if($db == null){
            error_log("Database not initialized");
            throw new Exception('Database not initialized');
            die("Database not initialized");
        }
    
        try{
            $stmt = $db->prepare('INSERT INTO AccessLog (user_id, ip_address, operating_system) VALUES (:user_id, :ip_address, :operating_system)');
            $user = User::getUserByEmail($db, $email);
            if ($user === null) {
                throw new Exception("User with the email $email not found.");
                die("User with the email $email not found.");
            }

            $userId = $user->getUserID();
            $ipAddress = User::getUserIpAddress();
            $operatingSystem = User::getUserOS();
            $stmt->execute(['user_id' => $userId, 'ip_address' => $ipAddress, 'operating_system' => $operatingSystem]);

        } catch (PDOException $e) {
            // PDO exceptions
            echo "Database error: " . $e->getMessage();
            die("Problem with database");
        } catch (Exception $e) {
            // TGeneral exceptions
            echo "An error occurred: " . $e->getMessage();
            die("An error occurred: " . $e->getMessage());
        }
    }
    

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
