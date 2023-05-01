<?php
function getAllTickets(PDO $connection){
    $stmt = $connection->prepare('SELECT * FROM Ticket');
    $stmt->execute();
    $tickets = $stmt->fetchAll();
    
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