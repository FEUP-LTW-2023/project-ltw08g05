<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <header>

         <?php
        // initialize database connection
        require_once('database/connection.php'); 
        require_once('database/ticket.php');
        $db = getDatabaseConnection();
        $tickets = getAllTickets($db);
        ?>
        
    </header>
    <main>

    <h1>Initial page</h1>

    
    </main>
</body>
</html>