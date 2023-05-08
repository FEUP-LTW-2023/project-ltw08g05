<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../../database/connection.php');

class Ticket {

  public ?int $id;
  public ?int $userID;
  public ?int $departmentID;
  public ?int $agentAssignedID;
  public ?string $title;
  public ?string $content;
  public ?string $status;
  public ?string $creationDate;
  public ?string $updateDate;

  public function __construct($id, $userID, $departmentID, $agentAssignedID, $title, $content, $status, $creationDate, $updateDate) {
    $this->id = $id;
    $this->userID = $userID;
    $this->departmentID = $departmentID;
    $this->agentAssignedID = $agentAssignedID;
    $this->title = $title;
    $this->content = $content;
    $this->status = $status;
    $this->creationDate = $creationDate;
    $this->updateDate = $updateDate;
  }

  static function getTicket(PDO $db, int $id) {
    $stmt = $db->prepare('SELECT id, id_user, id_department, agent_assigned, title, content_text, ticket_status, creation_date, update_date FROM Ticket WHERE id = ?');
    $stmt->execute(array($id));

    $ticket = $stmt->fetch();

    // check if ticket exists
    if (!$ticket) {
      echo "Oops, Ticket not found.";
      die("Ticket not found.");
    }
    return new Ticket(
        $ticket['id'], 
        $ticket['id_user'], 
        $ticket['id_department'], 
        $ticket['agent_assigned'], 
        $ticket['title'], 
        $ticket['content_text'], 
        $ticket['ticket_status'], 
        $ticket['creation_date'], 
        $ticket['update_date']
    );
  }  
  static function getAllTickets(PDO $db) {
      $stmt = $db->prepare('SELECT * FROM Ticket');
      $stmt->execute();

      $tickets = array();
      while ($ticket = $stmt->fetch()) {  
          // echo print_r($ticket);
          $tickets[] = new Ticket(
            $ticket['id'], 
            $ticket['id_user'], 
            $ticket['id_department'], 
            $ticket['agent_assigned'], 
            $ticket['title'], 
            $ticket['content_text'], 
            $ticket['ticket_status'], 
            $ticket['creation_date'], 
            $ticket['update_date']
          );
      }
      return $tickets;
  }

  public function add(PDO $db) {  

    $stmt = $db->prepare('
        INSERT INTO Ticket
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');

    $stmt->execute(array($this->id, $this->userID, $this->departmentID, $this->agentAssignedID, $this->title, $this->content, $this->status, $this->creationDate, $this->updateDate));
    $this->id = intval($db->lastInsertId('Ticket'));
  }

  function save(PDO $db) {
    $stmt = $db->prepare('
      UPDATE Ticket SET title = ?, content_text = ? WHERE id = ?
    ');

    $stmt->execute(array($this->title, $this->content, $this->id));
  }

}
?>