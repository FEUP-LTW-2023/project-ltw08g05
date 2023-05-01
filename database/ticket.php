<?php
function getAllTickets(PDO $connection){
    $stmt = $connection->prepare('SELECT * FROM Ticket');
    $stmt->execute();
    $tickets = $stmt->fetchAll();
    
    foreach( $tickets as $ticket) {
        echo '<h1>' . $ticket['title'] . '</h1>';
        echo '<p>' . $ticket['content_text'] . '</p>';
        }

}
?>