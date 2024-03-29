<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../../database/connection.php');


class Department {

    public ?int $id;
    public ?int $userID;
    public ?string $title;
    public ?string $creationDate;


    public function __construct(?int $id, ?int $userID, ?string $title, ?string $creationDate) {
        $this->id = $id;
        $this->userID = $userID;
        $this->title = $title;   
        $this->creationDate = $creationDate;
    }

    function save(PDO $db, $sessionEmail) {
        if ($db == null) {
            error_log("Database not initialized");
            throw new Exception('Database not initialized');
        }
    
        $stmt = $db->prepare('
          UPDATE Department 
          SET id_user = :id_user, 
              title = :title 
          WHERE id = :id
        ');
        $stmt->bindValue(':id_user', $this->userID);
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':id', $this->id);
        
        try {
            $stmt->execute();
            session_start();
            $_SESSION['email'] = $sessionEmail;
            error_log("session email:");
            error_log($_SESSION['email']);
        } catch (PDOException $e) {
            error_log('Error updating user data: ' . $e->getMessage());
            ?> <p> <?php echo "Error updating user data"; ?> </p> <br> <?php
            throw new Exception('Error updating user data');
        }
    }

    public function addDepartment(PDO $db) {
        $stmt = $db->prepare('
            INSERT INTO Department
            VALUES (?, ?, ?, ?)
        ');
    
        $stmt->execute(array($this->id, $this->userID, $this->title, $this->creationDate));
        $this->id = intval($db->lastInsertId('Department'));
    }

    static function getDepartment(PDO $db, ?int $id) {
        if($id === null) {
            return null;
        }

        $stmt = $db->prepare('SELECT id, id_user, title, creation_date FROM Department WHERE id = ?');
        $stmt->execute(array($id));
    
        $department = $stmt->fetch();
    
        // check if department exists
        if (!$department) {
          echo "Oops, Department not found.";
          die("Department not found.");
        }
        return new Department(
            $department['id'], 
            $department['id_user'], 
            $department['title'],
            $department['creation_date']
        );
    }

    static function getDepartmentsByAgent(PDO $db, ?int $agentId) {
        if($agentId === null) {
            return null;
        }

        $stmt = $db->prepare('
            SELECT id
            FROM Department
            WHERE id_user = ?
        ');

        $stmt->execute(array($agentId));
        $departments = array();

        while ($departmentID = $stmt->fetch()) {
        $stmt2 = $db->prepare('
            SELECT *
            FROM Department
            WHERE id = ?
        ');

        $stmt2->execute(array($departmentID['id']));
        $department = $stmt2->fetch();

        $departments[] = new Department(
            $department['id'], 
            $department['id_user'], 
            $department['title'], 
            $department['creation_date']
        );
        }
        return $departments;
    }

    static function deleteDepartment(PDO $db, $departmentId) {
        if ($db == null) {
            error_log("Database not initialized");
            throw new Exception('Database not initialized');
        }
    
        $stmt = $db->prepare('DELETE FROM Department WHERE id = :departmentId');
        $stmt->bindValue(':departmentId', $departmentId);
    
        try {
            $stmt->execute();
            session_start();
        } catch (PDOException $e) {
            error_log('Error deleting department: ' . $e->getMessage());
            echo "<p>Error deleting department</p><br>";
            throw new Exception('Error deleting department');
        }
    }

    static function getAllDepartments(PDO $db) {
        $stmt = $db->prepare('SELECT * FROM Department');
        $stmt->execute();

        $departments = array();
        while ($department = $stmt->fetch()) {  
            // echo print_r($department);
            $departments[] = new Department(
                $department['id'], 
                $department['id_user'], 
                $department['title'],
                $department['creation_date']
            );
        }
        return $departments;
    }
}

?>