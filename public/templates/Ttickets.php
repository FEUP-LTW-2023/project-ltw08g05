<?php
declare(strict_types=1); 
?>

<?php
require_once(__DIR__ . '/../../database/connection.php');

function output_ticket($ticket){ ?>
      <h2><?php echo $ticket['title']; ?></h2>
      <p><?php echo $ticket['content']; ?></p>
      <?php }

function output_ticket_list($tickets){?>
    <section id="FAQ">
      <?php foreach($tickets as $ticket) output_ticket($ticket); ?>
    </section>
  <?php } 
  
?>
