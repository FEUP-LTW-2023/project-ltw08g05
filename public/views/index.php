<?php
// session_start(['cookies_samesite' => 'Lax']);

require_once (__DIR__ . '/../templates/Tcommon.php');
require_once(__DIR__ . '/../../database/connection.php');
session_start();
$loggedIn = isset($_SESSION['email']);
$justRegistered = isset($_SESSION['justRegistered']);
$db = getDatabaseConnection();

drawHeader()
?>

<section class="card">
    <article class="home-card-message">
        <?php
            if($loggedIn){
                if($justRegistered){
                    echo "<p>Welcome new User, " . $_SESSION['email'] . "!</p><br>";
                }
                else{
                    echo "<p>Welcome Back, " . $_SESSION['email'] . "!</p><br>";
                }
            }
            else{
                echo "<p>Please Login to your account, or Create a new account</p>";
            }        
        ?>
        <br>
    </article>
</section>

<?php
drawFooter();
?>