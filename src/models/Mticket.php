<?php

function getAllTickets(PDO $dbConnection){
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
?>
<?php
function output_ticket($ticket){ ?>
    <h2><?php echo $ticket['title']; ?></h2>
    <p><?php echo $ticket['content_text']; ?></p>
    <?php } ?>

<?php

function output_ticket_list($tickets){?>
        <section id="FAQ">
          <?php foreach($tickets as $ticket) output_ticket($ticket); ?>
        </section>
      <?php } ?>

<?php
function updateTicket($id, $title, $content){
    
    $db = new PDO('sqlite:../../database/database.db');
    try{
        $stmt = $db->prepare('UPDATE Ticket SET title = ?, content_text = ? WHERE id = ?');
        $stmt->execute([$title, $content, $id]);
    } catch(PDOException $e) {
        echo "Oops, we've got a problem related to database connection:";
        ?> <br> <?php
        echo $e->getMessage();
      }

}

?>