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

    public static function userPasswordMatch($email, $password) {
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
            error_log("Credentials don't match. User data:");
            error_log('User data: ' . print_r($user, true));
            error_log($stmt->rowCount());
            error_log($email);
            error_log($password);
            return false;
        } catch(PDOException $e) {
           ?> <p> <?php echo "Oops, we've got a problem with database connection:"; ?> </p> <br> 
           <?php echo $e->getMessage();
        }
    }


    public static function userExists($email){
        $db = new PDO('sqlite:../../database/database.db');
        if($db == null)
            throw new Exception('Database not initialized');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            $stms = $db->prepare('SELECT * FROM User WHERE email = :email');
            $stms->bindParam(':email', $email);
            $stms->execute();
            $user = $stms->fetch();
            if($user) 
                return true;

            return false;
        }
        catch(PDOException $e){
          ?> <p> <?php echo "Oops, we've got a problem with database connection:"; ?> </p> <br> 
          <?php $e->getMessage();
        }
    }
    // --------------------------------------- getters ---------------------------------------
    public function getEmail(){
      return $this->email;
    }
    public function getName() {
        return $this->name;
    }
    /**
     * access a user's data using email
     */
    public static function getUserByEmail(PDO $db, $email): ?User {
      if ($db == null) {
          error_log("Database not initialized");
          throw new Exception('Database not initialized');
      }
  
      try {
          $stmt = $db->prepare('SELECT id, name, email FROM User WHERE email = :email');
          $stmt->bindParam(':email', $email);
          $stmt->execute();
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($user) {
              return new User($user['id'], $user['name'], $user['email']);
          } else {
              return null;
          }
      } catch(PDOException $e) {
          ?> <p> <?php echo "Oops, we've got a problem with database connection:"; ?> </p> <br> 
          <?php echo $e->getMessage();
      }
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
