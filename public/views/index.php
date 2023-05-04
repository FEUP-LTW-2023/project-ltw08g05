<?php
// session_start(['cookies_samesite' => 'Lax']);

require_once (__DIR__ . '/../templates/Tcommon.php');
require_once(__DIR__ . '/../../database/connection.php');

$db = getDatabaseConnection();

drawHeader();
?>

<section class="card">
    <article class="home-card-message">
        <p>Welcome Back!</p>
        <p>Please Login to your account, or Create a new account</p>    
        <br>
    </article>
</section>

<?php
drawFooter();
?>