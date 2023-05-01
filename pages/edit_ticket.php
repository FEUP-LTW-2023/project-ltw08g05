<?php
// check if id parameter is set
if (!isset($_GET['id'])) {
    die("Ticket ID not provided.");
}


// connect to database
try {
    $dbh = new PDO('sqlite:../database/database.db');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Oops, we've got a problem related to database connection:";
    die("Error connecting to database: " . $e->getMessage());
}

// retrieve article from database
$id = $_GET['id'];

$stmt = $dbh->prepare("SELECT * FROM Ticket WHERE id = ?");

$stmt->execute([$id]);
$ticket = $stmt->fetch();

// check if article exists
if (!$ticket) {
    echo "Oops, Ticket not found.";
    die("Ticket not found.");
}

// fill form fields with article values
$title = $article['title'];
$content = $article['content_text'];

?> 



<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
</head>
<body>
    <h1>Edit Article</h1>
    <p>current title: <?php echo $ticket['title'] ?> </p> 
    <p>current issue: <?php echo $ticket['content_text'] ?></p>
    <form action="action_edit_ticket.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="title">New Title:</label>
        <input type="text" name="title" value="<?php echo $title; ?>"><br><br>
        <label for="content">New Content:</label><br>
        <textarea name="content"><?php echo $content; ?></textarea><br><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>