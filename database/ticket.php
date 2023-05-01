<?php
function getAllTickets(PDO $connection){
    $stmt = $connection->prepare('SELECT * FROM Ticket');
    $stmt->execute();
    $tickets = $stmt->fetchAll();
    
    foreach( $tickets as $ticket) {
        echo '<h2>' . $ticket['title'] . '</h2>';
        echo '<p>' . $ticket['content_text'] . '</p>';
        }

}
?>