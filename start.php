<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <header>
         <?php
        $db = new PDO('sqlite:database/database.db');
        $stmt = $db->prepare('SELECT * FROM User');
        $stmt->execute();
        $articles = $stmt->fetchAll();
        
        foreach( $articles as $article) {
            echo '<h1>' . $article['username'] . '</h1>';
            echo '<p>' . $article['email'] . '</p>';
          }
    
        ?>
        
    </header>
    <main>

    <h1>Initial page</h1>

    
    </main>
</body>
</html>