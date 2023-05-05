<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once('Mticket.php');

class User {

    public ?int $userID = NULL;
    public ?string $email = NULL;
    public ?string $name = NULL;
    public ?array $tickets = null;
    public ?bool $isAgent = null;
    public ?bool $isAdmin = null;

    public function __construct($userID, $name, $email) {

        $this->userID = $userID;
        $this->name = $name;
        $this->email = $email;
    }

    function userExists($email, $password) {
        $db = new PDO('sqlite:../../database/database.db');
        if ($db == null)
        throw new Exception('Database not initialized');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('SELECT * FROM User WHERE email = :email AND password = :password');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $user = $stmt->fetch();
            if($user){
                return true;
            }
            error_log('User data: ' . print_r($user, true));
            error_log($stmt->rowCount());
            error_log($email);
            error_log($password);
            return false;
        } catch(PDOException $e) {
            echo "Oops, we've got a problem related to database connection:";
            ?> <br> <?php
            echo $e->getMessage();
        }
    }
    
    function name() {
        return $this->name;
    }

    static function getUser(PDO $db, int $id): User {
        $stmt = $db->prepare('
            SELECT userID, name, email
            FROM User 
            WHERE userID = ?
        ');

        $stmt->execute(array($id));
        $user = $stmt->fetch();

        return new User(
            $user['userID'],
            $user['name'],
            $user['email'],
        );
    }

    static function getAllUsers(PDO $db) {
        $stmt = $db->prepare('SELECT * FROM User');
        $stmt->execute();
    
        $users = array();
        while ($user = $stmt->fetch(PDO::FETCH_OBJ)) {
          $users[] = new User(
            $user->userID,
            $user->name,
            $user->email,
          );
        }
        return $users;
    }

    static function getUserTickets(PDO $db, int $id): array {
    
        $stmt = $db->prepare('
          SELECT id
          FROM Ticket
          WHERE id_user = ?
        ');
    
        $stmt->execute(array($id));
        $tickets = array();
    
        while ($tickID = $stmt->fetch(PDO::FETCH_OBJ)) {
          $stmt2 = $db->prepare('
            SELECT *
            FROM Ticket
            WHERE id = ?
          ');
    
          $stmt2->execute(array($tickID->ticketID));
          $ticket = $stmt2->fetch(PDO::FETCH_OBJ);
    
          $tickets[] = new Ticket(
            $ticket->ticketID,
            $ticket->userID,
            $ticket->departmentID,
            $ticket->agentAssignedID,
            $ticket->title,
            $ticket->content,
            $ticket->status,
            $ticket->creationDate, 
            $ticket->updateDate
          );
        }
        
        return $tickets;
      }


}
?>
