<?php
// check if user parameter is set
if (!isset($_GET['user'])) {
    die("Ticket ID not provided.");
}

// connect to database
try {
    $dbh = new PDO('sqlite:../../database/database.db');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Oops, we've got a problem related to database connection:";
    die("Error connecting to database: " . $e->getMessage());
}

// retrieve article from database
$username = $_GET['user'];

$stmt = $dbh->prepare("SELECT * FROM User WHERE username = ?");

$stmt->execute([$username]);
$user = $stmt->fetch();

// check if article exists
if (!$user) {
    echo "Oops, User not found.";
    die("User not found.");
}

// fill form fields with article values
$first_name = $user['first_name'];
$last_name = $user['last_name'];

// Page itself
require_once (__DIR__ . '/../templates/Tcommon.php');
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Musers.php');
require_once("../../src/controllers/LoginController.php");
session_start();
// check if the user is logged in
if (!isset($_SESSION['email'])) {
// if not, redirect to the login page
header('Location: login.php');
exit();
}
$db = getDatabaseConnection();
if ($db == null){
    throw new Exception('Database not initialized');
    ?> <p>$db is null</p> <?php
}
$email = $_SESSION['email'];
$current_user = User::getUserByEmail($db, $email);
$username = $current_user->getUsername();

drawHeader()
?>
    <head>
    <link href="../styles/profile.css" rel="stylesheet">
    </head>
    <h3>Edit User <?php echo $username?></h3><br><br> 
    <form action="../../src/controllers/action_edit_user.php" method="post">
        <div class="card mb-4">
        <div class="card-body">
            <div class="row">
              <div class="col-sm-9">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $username; ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo $first_name; ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo $last_name; ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="new_email">Email:</label>
                <input type="email" name="new_email" value="<?php echo $current_user->getEmail() ?>"><br><br>
                <input type="hidden" name="email" value="<?php echo $email?>">
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="address">Address:</label>
                <input type="text" name="address" value="<?php echo $current_user->getAddress() ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="country">Country:</label>
                <input type="text" name="country" value="<?php echo $current_user->getCountry() ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="city">City:</label>
                <input type="text" name="city" value="<?php echo $current_user->getCity() ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="zip_code">zip_code:</label>
                <input type="text" name="zip_code" value="<?php echo $current_user->getzip_code() ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="bio">Bio:</label>
                <textarea name="bio"><?php echo $current_user->getBio() ?></textarea><br><br>
              </div>
            </div>
          </div>
          <button class="submit" type="submit">Save Changes</button>
        </div>
    </form>
<?php
    drawFooter();
?>