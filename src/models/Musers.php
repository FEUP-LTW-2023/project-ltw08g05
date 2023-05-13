<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once('Mticket.php');

class User {

  public ?int $userID = NULL;
  public ?string $email = NULL;
  public ?string $firstName = NULL;
  public ?string $lastName = NULL;
  public ?string $username = NULL;
  public ?string $address = NULL;
  public ?string $country = NULL;
  public ?string $city = NULL;
  public ?string $zipCode = NULL;
  public ?string $bio = NULL;
  public ?bool $isAgent = null;
  public ?bool $isAdmin = null;
  
  public function __construct($userID, $email, $firstName, $lastName, $username, $address = null, $country = null, $city = null, $zipCode = null, $bio = null, $isAgent = false, $isAdmin = false) {
      $this->userID = $userID;
      $this->email = $email;
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->username = $username;
      $this->address = $address;
      $this->country = $country;
      $this->city = $city;
      $this->zipCode = $zipCode;
      $this->bio = $bio;
      $this->isAgent = $isAgent;
      $this->isAdmin = $isAdmin;
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
  public function getUserID() {
    return $this->userID;
  }
  
  public function getEmail() {
      return $this->email;
  }
  
  
  public function getFirstName() {
      return $this->firstName;
  }
  
  public function getLastName() {
      return $this->lastName;
  }
  
  public function getUsername() {
      return $this->username;
  }
  
  public function getAddress() {
      return $this->address;
  }
  
  public function getCountry() {
      return $this->country;
  }
  
  public function getCity() {
      return $this->city;
  }
  
  public function getZipCode() {
      return $this->zipCode;
  }
  
  public function getBio() {
      return $this->bio;
  }
  
  public function getIsAgent() {
      return $this->isAgent;
  }
  
  public function getIsAdmin() {
      return $this->isAdmin;
  }
  
  
  public function getFullName() {
      return $this->firstName . ' ' . $this->lastName;
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
        $stmt = $db->prepare('SELECT id, email, first_name, last_name, username, address, country, city, zip_code, bio, is_agent, is_admin FROM User WHERE email = :email');
        $stmt->bindParam(':email', $email);
          $stmt->execute();
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($user) {
            return new User(
              $user['id'],
              $user['email'],
              $user['first_name'],
              $user['last_name'],
              $user['username'],
              $user['address'],
              $user['country'],
              $user['city'],
              $user['zip_code'],
              $user['bio'],
              $user['is_agent'],
              $user['is_admin']
            );
            
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
        while ($user = $stmt->fetch()) {
          $users[] = new User(
            $user['id'],
            $user['email'],
            $user['first_name'],
            $user['last_name'],
            $user['username'],
            $user['address'],
            $user['country'],
            $user['city'],
            $user['zip_code'],
            $user['bio'],
            $user['is_agent'],
            $user['is_admin']
          );
          
        }
        return $users;
    }

    static function getAgents(PDO $db) {
      $stmt = $db->prepare('SELECT * FROM User WHERE is_agent = 1');
      $stmt->execute();
  
      $users = array();
      while ($user = $stmt->fetch()) {
        $users[] = new User(
          $user['id'],
          $user['email'],
          $user['first_name'],
          $user['last_name'],
          $user['username'],
          $user['address'],
          $user['country'],
          $user['city'],
          $user['zip_code'],
          $user['bio'],
          $user['is_agent'],
          $user['is_admin']
        );
        
      }
      return $users;
  }

    static function getUserTickets(PDO $db, int $id) {
    
        $stmt = $db->prepare('
          SELECT id
          FROM Ticket
          WHERE id_user = ?
        ');
    
        $stmt->execute(array($id));
        $tickets = array();
    
        while ($tickID = $stmt->fetch()) {
          $stmt2 = $db->prepare('
            SELECT *
            FROM Ticket
            WHERE id = ?
          ');
    
          $stmt2->execute(array($tickID['id']));
          $ticket = $stmt2->fetch();
    
          $tickets[] = new Ticket(
            $ticket['id'], 
            $ticket['id_user'], 
            $ticket['id_department'], 
            $ticket['agent_assigned'], 
            $ticket['title'], 
            $ticket['content_text'], 
            $ticket['response_text'], 
            $ticket['ticket_status'], 
            $ticket['creation_date'], 
            $ticket['update_date']
          );
        }
        
        return $tickets;
      }


}
?>
