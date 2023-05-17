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

    static function getDepartment(PDO $db, int $id) {
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