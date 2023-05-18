<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once('Mticket.php');

class User {

  public ?int $userID = NULL;
  public ?string $email = NULL;
  public ?string $first_name = NULL;
  public ?string $last_name = NULL;
  public ?string $username = NULL;
  public ?string $address = NULL;
  public ?string $country = NULL;
  public ?string $city = NULL;
  public ?string $zip_code = NULL;
  public ?string $bio = NULL;
  public ?bool $isAgent = null;
  public ?bool $isAdmin = null;
  
  public function __construct($userID, $email, $first_name, $last_name, $username, $address = null, $country = null, $city = null, $zip_code = null, $bio = null, $isAgent = false, $isAdmin = false) {
      $this->userID = $userID;
      $this->email = $email;
      $this->first_name = $first_name;
      $this->last_name = $last_name;
      $this->username = $username;
      $this->address = $address;
      $this->country = $country;
      $this->city = $city;
      $this->zip_code = $zip_code;
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

  function save(PDO $db) {
    if ($db == null) {
        error_log("Database not initialized");
        throw new Exception('Database not initialized');
    }

    $stmt = $db->prepare('
      UPDATE User 
      SET email = :email, 
          username = :username, 
          first_name = :first_name, 
          last_name = :last_name, 
          address = :address, 
          country = :country, 
          city = :city, 
          zip_code = :zip_code, 
          bio = :bio 
      WHERE id = :user_id
    ');
    error_log("save:");
    error_log($this->email);
    error_log($this->username);
    $stmt->bindValue(':email', $this->email);
    $stmt->bindValue(':username', $this->username);
    $stmt->bindValue(':first_name', $this->first_name);
    $stmt->bindValue(':last_name', $this->last_name);
    $stmt->bindValue(':address', $this->address);
    $stmt->bindValue(':country', $this->country);
    $stmt->bindValue(':city', $this->city);
    $stmt->bindValue(':zip_code', $this->zip_code);
    $stmt->bindValue(':bio', $this->bio);
    $stmt->bindValue(':user_id', $this->userID);
    
    try {
        $stmt->execute();
        session_start();
        $_SESSION['email'] = $this->email;
        error_log("session email:");
        error_log($_SESSION['email']);
    } catch (PDOException $e) {
        error_log('Error updating user data: ' . $e->getMessage());
        ?> <p> <?php echo "Error updating user data"; ?> </p> <br> <?php
        throw new Exception('Error updating user data');
    }
}


  // --------------------------------------- getters ---------------------------------------
  public function getUserID() {
    return $this->userID;
  }
  
  public function getEmail() {
      return $this->email;
  }
  
  
  public function getfirst_name() {
      return $this->first_name;
  }
  
  public function getlast_name() {
      return $this->last_name;
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
  
  public function getzip_code() {
      return $this->zip_code;
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
      return $this->first_name . ' ' . $this->last_name;
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
  
  static function getUserById(PDO $db, $id) {
    if ($id==null) {
      return null;
    }
    $stmt = $db->prepare('SELECT id, email, first_name, last_name, username, address, country, city, zip_code, bio, is_agent, is_admin FROM User WHERE id = ?');
    $stmt->execute(array($id));

    $user = $stmt->fetch();

    // check if ticket exists
    if (!$user) {
      echo "Oops, User not found.";
      die("User not found.");
    }
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

  

}
?>
