<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../../database/connection.php');

class Ticket {

  public ?int $ticketID;
  public ?int $userID;
  public ?int $departmentID;
  public ?int $agentAssignedID;
  public ?string $title;
  public ?string $content;
  public ?string $status;
  public ?string $creationDate;
  public ?string $updateDate;

  public function __construct(?int $ticketID, ?string $userID, ?int $departmentID, ?int $agentAssignedID, ?string $title, ?string $content, ?string $status, ?string $creationDate, ?string $updateDate) {
    $this->ticketID = $ticketID;
    $this->userID = $userID;
    $this->departmentID = $departmentID;
    $this->agentAssignedID = $agentAssignedID;
    $this->title = $title;
    $this->content = $content;
    $this->status = $status;
    $this->creationDate = $creationDate;
    $this->updateDate = $updateDate;
  }

  static function getAllTickets(PDO $dbConnection){
      $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try{
          $stmt = $dbConnection->prepare('SELECT * FROM Ticket');
          $stmt->execute();
          $tickets = $stmt->fetchAll();
      } catch(PDOException $e) {
          echo "Oops, we've got a problem related to database connection:";
          ?> <br> <?php
          echo $e->getMessage();
        }

      return $tickets;

  }
  
  static function updateTicket($id, $title, $content){
      
      $db = getDatabaseConnection();
      try{
          $stmt = $db->prepare('UPDATE Ticket SET title = ?, content_text = ? WHERE id = ?');
          $stmt->execute([$title, $content, $id]);
      } catch(PDOException $e) {
          echo "Oops, we've got a problem related to database connection:";
          ?> <br> <?php
          echo $e->getMessage();
        }

  }

}
?>