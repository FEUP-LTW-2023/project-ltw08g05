<?php
/**
 * It is generally recommended to include the code for database connection 
 * at the top of the page, before any HTML output is sent.
*/


// require_once('../database/connection.php');
require_once('../../src/models/Mfaq.php');
require_once('../templates/Tcommon.php');
$db = new PDO('sqlite:../../database/database.db');
if ($db == null)
  throw new Exception('Database not initialized');
$tickets = getAllFAQ($db);
drawHeader($db);

?>
    <h1>FAQ</h1>
    <p>Here are the most frequently asked questions:</p>
<?php
output_FAQ_list($tickets);    
drawFooter();
?>