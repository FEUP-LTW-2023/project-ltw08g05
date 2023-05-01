<?php
function getAllTickets(PDO $connection){
    $stmt = $connection->prepare('SELECT * FROM FAQ');
    $stmt->execute();
    $tickets = $stmt->fetchAll();
    
    foreach( $tickets as $ticket) {
        echo '<h2>' . $ticket['question'] . '</h2>';
        echo '<p>' . $ticket['answer'] . '</p>';
        }

}
?>