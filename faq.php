<?php
/**
 * It is generally recommended to include the code for database connection 
 * at the top of the page, before any HTML output is sent.
*/

// initialize database connection
require_once('database/connection.php');
require_once('database/faq.php');
require_once('templates/common.php');
$db = getDatabaseConnection();
$tickets = getAllFAQ($db);
output_header();

?>
    <h1>FAQ</h1>
    <p>Here are the most frequently asked questions:</p>
<?php
output_FAQ_list($tickets);    
output_footer();
?>