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

        // Get data
        $email = $_POST['email'];
        $newUserType = $_POST['userType'];
        $userId = intVal($_POST['userId']);

        // Validate email
        if ($email === false) {
            error_log("Invalid email format");
            $_SESSION['error_message'] = "Invalid email format";
            header("Location: /public/views/ticket.php?id=".$id);
            exit();
        }

        // Validate newUserType
        if ($newUserType === false) {
            error_log("Invalid user type format");
            $_SESSION['error_message'] = "Invalid user type format";
            header("Location: /public/views/admin_management.php");
            exit();
        }

        // Validate userId
        if ($userId === false || $userId <= 0) {
            error_log("Invalid user ID");
            $_SESSION['error_message'] = "Invalid user ID";
            header("Location: /public/views/admin_management.php");
            exit();
        }

        $db = getDatabaseConnection();
        if($db == null){
            die('Database error');
        }
        $current_user = User::getUserByEmail($db, $email);
        if($current_user == null){
            die('User not found');
        }

        $userToEdit = User::getUserById($db, $userId);

        if($current_user->getIsAdmin()) {
            $userToEdit->saveUserType($db, $newUserType, $current_user);
            header("Location: /public/views/admin_management.php");
            exit();
        } else {
            error_log("Only Admins can perform this action.");
            $_SESSION['error_message'] = "User as no permissions.";
            header("Location: /public/views/index.php");
            exit();
        }
    }
?>
