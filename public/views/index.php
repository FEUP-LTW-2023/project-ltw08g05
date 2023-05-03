<?php
// session_start(['cookies_samesite' => 'Lax']);

require_once (__DIR__ . '/../templates/Tcommon.php');
require_once(__DIR__ . '/../../database/connection.php');

$db = getDatabaseConnection();

drawHeader()
?>

<div class="">
    <div class="">
        <p>Welcome Back!</p>
        <p>Please Login to your account, or Create a new account</p>    
        <br>
    </div>
</div>

<?php
drawFooter();
?>