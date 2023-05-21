<?php
    require_once(__DIR__ . '/../../database/connection.php');
    require_once '../models/Mdep.php';
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
      $newDepartmentName = $_POST['departmentName'];
      $newAgentId = $_POST['departmentAgentId'];
      $departmentId = intVal($_POST['departmentId']);

      // Validate email
      if ($email === false) {
        error_log("Invalid email format");
        $_SESSION['error_message'] = "Invalid email format";
        header("Location: /public/views/admin_management.php");
        exit();
      }

      // Validate newDepartmentName
      if ($newDepartmentName === null || $newDepartmentName === "") {
        error_log("Department name is required");
        $_SESSION['error_message'] = "Department name is required";
        header("Location: /public/views/admin_management.php");
        exit();
      }

      // Validate newAgentId
      if ($newAgentId === false || $newAgentId <= 0) {
        error_log("Invalid Agent ID");
        $_SESSION['error_message'] = "Invalid agent ID";
        header("Location: /public/views/admin_management.php");
        exit();
      }

      // Validate department ID
      if ($departmentId === false || $departmentId <= 0) {
        error_log("Invalid department ID");
        $_SESSION['error_message'] = "Invalid department ID";
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

      $department = Department::getDepartment($db, $departmentId);

      if($current_user->getIsAdmin()) {
        $department->userID = $newAgentId;
        $department->title = $newDepartmentName;
        $department->save($db, $_SESSION['email']);
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