<?php
function getAllFAQ(PDO $connection){
    $stmt = $connection->prepare('SELECT * FROM FAQ');
    $stmt->execute();
    $tickets = $stmt->fetchAll();
    
    return $tickets;
}

function output_FAQ($ticket){ ?>
    <h2><?php echo $ticket['question']; ?></h2>
    <p><?php echo $ticket['answer']; ?></p>
    <?php } ?>

<?php

function output_FAQ_list($tickets){?>
        <section id="FAQ">
          <?php foreach($tickets as $ticket) output_FAQ($ticket); ?>
        </section>
      <?php } ?>
