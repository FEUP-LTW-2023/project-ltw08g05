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

  static function searchTickets(PDO $db, string $search) : array {
      $stmt = $db->prepare('SELECT * FROM Ticket WHERE title LIKE ?');
      $stmt->execute(array($search . '%'));

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

  static function getDepartmentTickets(PDO $db, $departmentID) {
    $stmt = $db->prepare('
      SELECT id
      FROM Ticket
      WHERE id_department = ?
    ');

    $stmt->execute(array($departmentID));
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
        $ticket['ticket_status'], 
        $ticket['creation_date'], 
        $ticket['update_date']
      );
    }
    return $tickets;
  }

  static function getTicketsByAgent(PDO $db, $agentID) {
    if($agentID === null) {
      return null;
    }

    $stmt = $db->prepare('
      SELECT id
      FROM Ticket
      WHERE agent_assigned = ?
    ');

    $stmt->execute(array($agentID));
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
        $ticket['ticket_status'], 
        $ticket['creation_date'], 
        $ticket['update_date']
      );
    }
    return $tickets;
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
        $ticket['ticket_status'], 
        $ticket['creation_date'], 
        $ticket['update_date']
      );
    }
    return $tickets;
  }

  static function searchUserTickets(PDO $db, string $search, $id) : array {
    $stmt = $db->prepare('SELECT * FROM Ticket WHERE id_user = ? AND title LIKE ?');
    $stmt->execute(array($id, $search . '%'));

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
        $ticket['ticket_status'], 
        $ticket['creation_date'], 
        $ticket['update_date']
      );
    }
    return $tickets;
  }


    /**
     * Gets the ticket history
     */
    public static function getTicketHistory(PDO $db, int $ticket_id) {
      $stmt = $db->prepare('SELECT * FROM TicketHistory WHERE ticket_id = ? ORDER BY change_time DESC');
      $stmt->execute(array($ticket_id));

      $history = array();
      while ($row = $stmt->fetch()) {
          $history[] = $row;
      }

      return $history;
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
      UPDATE Ticket SET title = ?, content_text = ? , id_department = ? WHERE id = ?
    ');

    $stmt->execute(array($this->title, $this->content, $this->departmentID, $this->id));
  }

  function saveDep(PDO $db) {
    $stmt = $db->prepare('
      UPDATE Ticket SET id_department = ? WHERE id = ?
    ');

    $stmt->execute(array($this->departmentID, $this->id));
  }
  
  function saveStatus(PDO $db) {
    $stmt = $db->prepare('
      UPDATE Ticket SET ticket_status = ? WHERE id = ?
    ');
    
    $stmt->execute(array($this->status, $this->id));
  }

  function saveAssign(PDO $db) {
    $stmt = $db->prepare('
      UPDATE Ticket SET agent_assigned = ? WHERE id = ?
    ');
    $stmt->execute(array($this->agentAssignedID, $this->id));
  }

  static function deleteTicket($db, $ticketId) {
    if ($db == null) {
      error_log("Database not initialized");
      throw new Exception('Database not initialized');
    }

    $stmt = $db->prepare('DELETE FROM Ticket WHERE id = :ticketId');
    $stmt->bindValue(':ticketId', $ticketId);

    try {
      $stmt->execute();
      session_start();
    } catch (PDOException $e) {
      error_log('Error deleting ticket: ' . $e->getMessage());
      echo "<p>Error deleting ticket</p><br>";
      throw new Exception('Error deleting ticket');
    }
  }

  static function saveHistory(PDO $db, int $ticketId, int $userId, string $field, $oldValue, $newValue) {
      $stmt = $db->prepare('
          INSERT INTO TicketHistory (ticket_id, user_id, change_time, field_name, old_value, new_value)
          VALUES (?, ?, datetime("now"), ?, ?, ?)
      ');

      $stmt->execute(array($ticketId, $userId, $field, $oldValue, $newValue));
  }


}
?>