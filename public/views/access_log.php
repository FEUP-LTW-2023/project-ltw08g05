<?php
require_once (__DIR__ . '/../templates/Tcommon.php'); // session is declared in Tcommon.php
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/controllers/AccessLogController.php');
require_once('../../src/models/Musers.php');

if (!isset($_GET['user'])) {
    die("You don't have enough permissions.");
}

if (!isset($_SESSION['email'])) {
    header('Location: src/controllers/logout.php');
    exit();
}



$db = getDatabaseConnection();
if ($db == null) {
    throw new Exception('Database not initialized');
    error_log("Oops, we've got a problem related to the database connection: ".$e->getMessage());
    die("Error connecting to the database: " . $e->getMessage());
}

/**
 * additional access control.
 * Only the logged in user or an admin can view the access log.
 */
$email = $_SESSION['email'];
$current_user = User::getUserByEmail($db, $email);
$loggedInUsername = $current_user->getUsername();
$requestedUsername = $_GET['user'];
// Check if the logged in user has the right to view the requested user's logs
if ($loggedInUsername !== $requestedUsername && $current_user->getisAdmin() === 0) {
    die("You do not have the permissions to view this user's logs.");
}



$username = $_GET['user'];
$stmt = $db->prepare("SELECT * FROM User WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    error_log("User not found.");
    die("Permission denied.");
}

$username = $current_user->getUsername();
$userId = $current_user->getUserID();
$logs = AccessLogController::getLog($db, $userId);

drawHeader($db);
?>
    <head>
    <link href="../styles/login.css" rel="stylesheet">
    </head>

    <div class="centered2">
    <h4>Login History</h4>
    <h4><?php echo $username?></h4><br><br> 
    <form action="profile.php" method="get"> 
        <button type="submit" >Go back</button>    
    </form>
    </div>
    <div class="centered">
    <?php
        if (count($logs) > 0) {
            echo "<table>";
            echo "<thead><tr><th>Access Time</th><th>IP Address</th><th>Operating System</th></tr></thead>";
            echo "<tbody>";
            
            foreach ($logs as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['access_time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ip_address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['operating_system']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No access logs found for this user.";
        }
    ?>
    </div>
<?php
drawFooter();
?>