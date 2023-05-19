<?php 
declare(strict_types = 1);
require_once(__DIR__ . '/../../database/connection.php');

class Message {
  public ?int $id;
  public ?int $userID;
  public ?int $ticketID;
  public ?string $message;
  public ?string $creationDate;

  public function __construct($id, $userID, $ticketID, $message, $creationDate) {
    $this->id = $id;
    $this->userID = $userID;
    $this->ticketID = $ticketID;
    $this->message = $message;
    $this->creationDate = $creationDate;
  }

  public static function getMessages(PDO $db, int $ticketID): array {
    $stmt = $db->prepare('SELECT * FROM Message WHERE id_ticket = ? ORDER BY creation_date ASC');
    $stmt->execute([$ticketID]);

    $messages = array();
    while ($message = $stmt->fetch()) {
      $messages[] = new Message(
        $message['id'],
        $message['id_user'],
        $message['id_ticket'],
        $message['message'],
        $message['creation_date']
      );
    }
    return $messages;
  }

  public function sendMessage(PDO $db) {
    $stmt = $db->prepare('INSERT INTO Message (id_user, id_ticket, message) VALUES (?, ?, ?)');
    $stmt->execute([$this->userID, $this->ticketID, $this->message]);
    $this->id = intval($db->lastInsertId('Message'));
    echo(print_r($this));
  }
}

?>