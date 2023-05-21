<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once '../models/Musers.php';
require_once '../models/Mdep.php';
require_once '../models/Mmessage.php';

session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}

$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF 
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF verification failed!');
    }
    // get data
    $title = $_POST['title'];
    $agentId = intval($_POST['agent']);
    $current_user = User::getUserByEmail($db, $_SESSION['email']);
    $userID = $current_user->getUserId();

    if($agentId == null) {
        $_SESSION['error_message'] = 'Please select an agent';
        header('Location: /public/views/add_department.php');
        exit();
    }
    if($title == null) {
        $_SESSION['error_message'] = 'Please enter a name for the department';
        header('Location: /public/views/add_department.php');
        exit();
    }

    $department = new Department(
        null, 
        $agentId,
        $title,
        date("Y-m-d")
    );

    $department->addDepartment($db);

    header("Location: ../../public/views/admin_management.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>