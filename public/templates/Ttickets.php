<?php
declare(strict_types=1); 
?>

<?php
require_once(__DIR__ . '/../../database/connection.php');

function drawAllTickets($tickets){?>
    <section id="ticket-page">
      <header>
        <h2>Tickets</h2>
        <input id="searchticket" type="text" placeholder="search">
      </header>
      <section id="tickets">
        <?php foreach($tickets as $ticket) { ?> 
          <article class="card">
            <h4 class="ticket-title"> <?=$ticket['title'];?> </h4>
            <p class="ticket-content"> <?=$ticket['content_text'];?> </p>
          </article>
        <?php } ?>
      </section>
    </section>
   
  <?php } 
?>
