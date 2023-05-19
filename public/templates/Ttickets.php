<?php
declare(strict_types=1); 
?>

<?php
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Mticket.php');
require_once('../../src/models/Musers.php');

session_start();
$loggedIn = isset($_SESSION['email']);

function drawAllTickets($tickets, $current_user){?>
    <section id="tickets-page">
      <header>
        <h2>Tickets</h2>
      </header>
      <section id="tickets">
        <span class="search-bar">
          <input id="search-ticket" type="text" name="search" placeholder="Search">
          <svg class="search-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
              width="632.399px" height="632.399px" viewBox="0 0 632.399 632.399" style="enable-background:new 0 0 632.399 632.399;"
              xml:space="preserve">
            <path d="M255.108,0C119.863,0,10.204,109.66,10.204,244.904c0,135.245,109.659,244.905,244.904,244.905c52.006,0,100.238-16.223,139.883-43.854l185.205,185.176c1.671,1.672,4.379,1.672,5.964,0.115l34.892-34.891c1.613-1.613,1.47-4.379-0.115-5.965L438.151,407.605c38.493-43.246,61.86-100.237,61.86-162.702C500.012,109.66,390.353,0,255.108,0z M255.108,460.996c-119.34,0-216.092-96.752-216.092-216.092c0-119.34,96.751-216.091,216.092-216.091s216.091,96.751,216.091,216.091C471.199,364.244,374.448,460.996,255.108,460.996z"/>
          </svg>
          <svg class="filter-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44.023 44.023" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 44.023 44.023">
            <path d="m43.729,.29c-0.219-0.22-0.513-0.303-0.799-0.276h-41.831c-0.286-0.026-0.579,0.057-0.798,0.276-0.09,0.09-0.155,0.195-0.203,0.306-0.059,0.128-0.098,0.268-0.098,0.418 0,0.292 0.129,0.549 0.329,0.731l14.671,20.539v20.662c-0.008,0.152 0.015,0.304 0.077,0.446 0.149,0.364 0.505,0.621 0.923,0.621 0.303,0 0.565-0.142 0.749-0.354l11.98-11.953c0.227-0.227 0.307-0.533 0.271-0.828v-8.589l14.729-20.583c0.392-0.391 0.392-1.025 0-1.416zm-16.445,20.998c-0.209,0.209-0.298,0.485-0.284,0.759v8.553l-10,9.977v-18.53c0.014-0.273-0.075-0.55-0.284-0.759l-13.767-19.274h38.128l-13.793,19.274z"/>
          </svg>
        </span>
        <a href="add_ticket.php">
          <article class="card ticketCard">
            <svg class="add-ticket-icon" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><title/><g id="plus"><line class="cls-1" x1="16" x2="16" y1="7" y2="25"/><line class="cls-1" x1="7" x2="25" y1="16" y2="16"/></g></svg>
          </article>
        </a>
        <section id="all-tickets">
          <?php foreach($tickets as $ticket) { ?> 
            <a href="../views/ticket.php?id=<?=$ticket->id?>">
              <article class="card ticketCard">
                <h4 class="ticket-title"> <?= $ticket->title ?> </h4>
              </article>
            </a>
          <?php } ?>
        </section>
      </section>
    </section>
   
  <?php } 
?>

<?php function drawTicket($ticket, $current_user, $dep) { ?>
  
  <section id="single-ticket-page">
      <header>
          <h2> <?= $dep->title ?> ></h2>
          <h2> <?= $ticket->title ?> </h2>
          <!-- Author of the ticket -->
          <?php if($ticket->userID===$current_user->getUserID()) {  ?>
            <a href="edit_ticket.php?id=<?=$ticket->id?>"> Edit </a>  
          <?php } ?>
          <!-- agent whos not the author can only change department -->
          <?php if($current_user->getIsAgent() === 1 && $ticket->userID!=$current_user->getUserID()) {  ?>
            <a href="edit_ticket.php?id=<?=$ticket->id?>"> Department </a>
          <?php } ?>
          <!-- Normal Agents -->
          <?php  if($current_user->getIsAgent() === 1) { ?>
            <a href="ticket_history.php?id=<?=$ticket->id?>"> View edit history </a>  
            <a href="assign_ticket.php?id=<?=$ticket->id?>"> Assign </a>
            <div class="status-container">
              <a href="#" class="status-toggle"> Status </a>
              <div class="status-buttons">
                <button class="status-button" data-status="Open" data-ticket-id="<?= $ticket->id ?>">Open</button>
                <button class="status-button" data-status="Assigned" data-ticket-id="<?= $ticket->id ?>">Assigned</button>
                <button class="status-button" data-status="Closed" data-ticket-id="<?= $ticket->id ?>">Closed</button>
              </div>
            </div>
          <?php } ?>
        </header>

      <section class="container">
          <h2>Message Chat</h2>
          <section class="chat-window">
            <article class="user-message"> <?= $ticket->content ?> </article>
            <?php  /*if($ticket->response!=NULL) { ?>
              <article class="agent-message"> <?= $ticket->response ?> </article>
            <?php } */?>
          </section>
        <?php  if(($current_user->getIsAgent() && $ticket->agentAssignedID===$current_user->userID) || $ticket->userID===$current_user->getUserID()) { ?>  
          <!--<form class="chat-form" action="../../src/controllers/action_agent_response.php" method="post">-->
          <form class="chat-form">
            <input type="hidden" name="user_id" value="<?=$current_user->userID?>">
            <input type="hidden" name="id" value="<?=$ticket->id?>">
            <textarea id="message" name="message" placeholder="Enter your message..."></textarea>
            <button type="submit">Send</button>
          </form>
        <?php } elseif($current_user->getIsAgent()){ ?>
          <section class="card">
            <article class="home-card-message">
              <?php
                echo "You are not assigned to this ticket!";
              ?>
              <br>
            </article>
          </section>
        <?php } ?>

      </section>
    
  </section>
<?php } ?>


<?php 
/**
 *  Draws the edit ticket history page
 */
function drawTicketHistory(PDO $db, int $ticket_id, User $current_user) {
  $history = Ticket::getTicketHistory($db, $ticket_id);
  
  ?>
      <table>
          <thead>
              <tr>
                  <th>Change Time</th>
                  <th>User</th>
                  <th>Field</th>
                  <th>Old Value</th>
                  <th>New Value</th>
              </tr>
          </thead>
          <tbody>
              <?php if(empty($history)) { ?>
                  <tr>
                      <td colspan="5">No changes have been made to this ticket.</td>
                  </tr>
                  <?php } 
                  else{ ?>
              <?php foreach ($history as $change) { ?>
                  <tr>
                      <td><?php echo htmlspecialchars($change['change_time']); ?></td>
                      <td><?php echo htmlspecialchars($current_user->getUserName()); ?></td>
                      <td><?php echo htmlspecialchars($change['field_name']); ?></td>
                      <td><?php echo htmlspecialchars($change['old_value']); ?></td>
                      <td><?php echo htmlspecialchars($change['new_value']); ?></td>
                  </tr>
              <?php }} ?>
          </tbody>
      </table>
  <?php } ?>


<?php function drawEditTicket($ticket, $current_user, $deps) { ?>

  <form action="../../src/controllers/action_edit_ticket.php" method="post">
    <?php  if($current_user->getIsAgent() && $ticket->userID!=$current_user->getUserID()) { ?>
      <h1>Change Department</h1>
      <?php foreach($deps as $dep) { ?> 
        <input type="hidden" name="id" value=" <?= $ticket->id ?>">
        <input type="radio" id="dep<?= $dep->id ?>" name="department" value="<?= $dep->id ?>">
        <label for="dep<?= $dep->id ?>"><?= $dep->title ?></label><br>
      <?php } ?>
      <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
    <?php } else{ ?>  
      <h1>Edit Ticket</h1>
      <input type="hidden" name="id" value=" <?= $ticket->id ?>">
      <label for="title">New Title:</label>
      <input type="text" name="title" value=" <?= $ticket->title; ?> "><br><br>
      <label for="content">New Content:</label><br>
      <textarea name="content"> <?= $ticket->content; ?> </textarea><br><br>
      <h1>Change Department</h1>
      <?php foreach($deps as $dep) { ?> 
        <input type="hidden" name="id" value=" <?= $ticket->id ?>">
        <input type="radio" id="dep<?= $dep->id ?>" name="department" value="<?= $dep->id ?>">
        <label for="dep<?= $dep->id ?>"><?= $dep->title ?></label><br>
      <?php } ?>
      <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
    <?php } ?> 
    <button type="submit">Save</button>
  </form>
  
<?php } ?>

<?php function drawAssignTicket($ticket, $agents, $assigned_agent) { ?>
  <h1>Assign Ticket</h1><br>
  <p>Current status: <?= $ticket->status ?> </p>
  <?php if($assigned_agent!=NULL){ ?>
    <p>Current assignee: <?= $assigned_agent->first_name ?> <?= $assigned_agent->last_name ?> </p>
  <?php } ?>
  <form action="../../src/controllers/action_assign_ticket.php" method="post">
      <h4>New Assignee</h4><br>
      <input type="hidden" name="id" value=" <?= $ticket->id ?>">
      <?php foreach($agents as $agent) { ?> 
        <input type="radio" id="agent<?= $agent->id ?>" name="assignee" value="<?= $agent->email ?>">
        <label for="agent<?= $agent->id ?>"><?= $agent->first_name ?> <?= $agent->last_name ?></label><br>
      <?php } ?>
      <button type="submit">Save</button>
  </form>
<?php } ?>

<?php function drawAddTicket($deps) { ?>
  <section class="form-container">
    <h1>New Ticket</h1>
    <form action="../../src/controllers/action_create_ticket.php" method="post">

        <input type="text" name="title" placeholder="Title"><br>
        
        <section class="radio-group">
          <?php foreach($deps as $dep) { ?> 
            <label class="radio-label">
              <input type="radio" name="department" value="<?= $dep->id ?>">
              <?= $dep->title ?>
              <span class="radio-button"></span>
            </label>
          <?php } ?>
        </section>
        <br><textarea name="content" placeholder="Write here the content of your ticket"></textarea><br>

        <button type="submit">Submit</button>
    </form>
    <?php if (isset($_SESSION['error_message'])): ?>
      <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
  </section>
  
<?php } ?>