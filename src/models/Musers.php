<?php
function userExists($email, $password) {
    $db = new PDO('sqlite:../../database/database.db');
    if ($db == null)
    throw new Exception('Database not initialized');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $db->prepare('SELECT * FROM User WHERE email = :email AND password = :password');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch();
        if($user){
            return true;
        }
        error_log('User data: ' . print_r($user, true));
        error_log($stmt->rowCount());
        error_log($email);
        error_log($password);
        return false;
    } catch(PDOException $e) {
        echo "Oops, we've got a problem related to database connection:";
        ?> <br> <?php
        echo $e->getMessage();
    }
}
?>
