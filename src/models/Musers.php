<?php
function userExists($email, $password) {
    $db = new PDO('sqlite:database.db');
    if ($db == null)
        throw new Exception('Database not initialized');
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $db->prepare('SELECT * FROM User WHERE email = :email AND pass = :password');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    } catch(PDOException $e) {
        echo "Oops, we've got a problem related to database connection:";
        ?> <br> <?php
        echo $e->getMessage();
    }
}
?>
