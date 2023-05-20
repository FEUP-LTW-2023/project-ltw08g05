<?php
session_start();
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/controllers/AccessLogController.php');
require_once (__DIR__ . '/../templates/Tcommon.php');
require_once('../../src/models/Musers.php');

// check if user parameter is set
if (!isset($_GET['user'])) {
    die("You don't have enough permissions.");
}

// check if the user is logged in
if (!isset($_SESSION['email'])) {
    // if not, unset all session variables and redirect to the login
    header('Location: src/controllers/logout.php');
    exit();
}

$db = getDatabaseConnection();
if ($db == null) {
    throw new Exception('Database not initialized');
    echo "Oops, we've got a problem related to the database connection:";
    die("Error connecting to the database: " . $e->getMessage());
}


// retrieve the user from the database
$username = $_GET['user'];
$stmt = $db->prepare("SELECT * FROM User WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

// check if user exists
if (!$user) {
    echo "Oops, User not found.";
    die("User not found.");
}

$email = $_SESSION['email'];
$current_user = User::getUserByEmail($db, $email);
$username = $current_user->getUsername();
$userId = $current_user->getUserID();
$logs = AccessLogController::getLog($db, $userId);

// Page itself
drawHeader();
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
