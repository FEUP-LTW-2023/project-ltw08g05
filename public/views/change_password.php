<?php
session_start();

// check if user parameter is set
if (!isset($_POST['user'])) {
    die("You don't have enough permissions.");
}

// connect to the database
try {
    $dbh = new PDO('sqlite:../../database/database.db');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Oops, we've got a problem related to the database connection:";
    die("Error connecting to the database: " . $e->getMessage());
}

// retrieve the user from the database
$username = $_POST['user'];

$stmt = $dbh->prepare("SELECT * FROM User WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

// check if user exists
if (!$user) {
    echo "Oops, User not found.";
    die("User not found.");
}

$first_name = $user['first_name'];
$last_name = $user['last_name'];

// Page itself
require_once (__DIR__ . '/../templates/Tcommon.php');
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Musers.php');

// check if the user is logged in
if (!isset($_SESSION['email'])) {
    // if not, redirect to the login page
    header('Location: login.php');
    exit();
}
$db = getDatabaseConnection();
if ($db == null) {
    throw new Exception('Database not initialized');
    ?> <p>$db is null</p> <?php
}
$email = $_SESSION['email'];
$current_user = User::getUserByEmail($db, $email);
$username = $current_user->getUsername();

drawHeader();
?>
    <head>
    <link href="../styles/register.css" rel="stylesheet">
    </head>

    <h4>Change Password</h4>
    <h4><?php echo $username?></h4><br><br> 
    <form action="../../src/controllers/action_change_password.php" method="post">
        <div class="card mb-4">    
        <div class="card-body">
            <div class="row">
              <div class="col-sm-9">
                <label for="current_password">Current Password: <span class="required">*</span></label>
                <input type="password" name="current_password" id="current_password"><br><br>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-9">
                <label for="new_password">New Password: <span class="required">*</span></label>
                <input type="password" name="new_password" id="new_password"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="confirm_password">Confirm Password: <span class="required">*</span></label>
                <input type="password" name="confirm_password" id="confirm_password"><br><br>
              </div>
            </div>
          </div>
          <input type="hidden" name="email" value="<?php echo $email ?>">
          <button class="submit" type="submit">Save Changes</button>
        </div>
    </form>
<?php
drawFooter();
?>
