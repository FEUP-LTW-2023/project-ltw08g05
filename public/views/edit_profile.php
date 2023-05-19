<?php
require_once (__DIR__ . '/../templates/Tcommon.php');
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Musers.php');
require_once("../../src/controllers/LoginController.php");

if (!isset($_GET['user'])) {
    die("You don't have enough permissions.");
}

if (!isset($_SESSION['email'])) {
  header('Location: /src/controllers/logout.php');
  exit();
}

$db = getDatabaseConnection();
if ($db == null){
    throw new Exception('Database not initialized');
    error_log("Oops, we've got a problem related to database connection:");
    die("edit_profile.php: Error connecting to database: " . $e->getMessage());
}


$email = $_SESSION['email'];
$current_user = User::getUserByEmail($db, $email);
$username = $current_user->getUsername();

if (!$current_user) {
    echo "Oops, User not found.";
    error_log("edit_profile.php: User not found.");
    die("User not found.");
}
$first_name = $current_user->getfirst_name();
$last_name = $current_user->getlast_name();


drawHeader()
?>
    <head>
    <link href="../styles/profile.css" rel="stylesheet">
    </head>

    <h4>Edit User <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8')?></h4><br><br>
    <form action="../../src/controllers/action_edit_profile.php" method="post">
        <div class="card mb-4">    
        <div class="card-body">
            <div class="row">
              <div class="col-sm-9">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="new_email">Email:</label>
                <input type="email" name="new_email" value="<?php echo htmlspecialchars($current_user->getEmail(), ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="address">Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($current_user->getAddress(), ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="country">Country:</label>
                <input type="text" name="country" value="<?php echo htmlspecialchars($current_user->getCountry(), ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="city">City:</label>
                <input type="text" name="city" value="<?php echo htmlspecialchars($current_user->getCity(), ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="zip_code">zip_code:</label>
                <input type="text" name="zip_code" value="<?php echo htmlspecialchars($current_user->getzip_code(), ENT_QUOTES, 'UTF-8'); ?>"><br><br>
              </div>
            </div>
            <hr>
            <br>
            <div class="row">
              <div class="col-sm-9">
                <label for="bio">Bio:</label>
                <textarea name="bio"><?php echo htmlspecialchars($current_user->getBio(), ENT_QUOTES, 'UTF-8') ?></textarea><br><br>
              </div>
            </div>
          </div>
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8')?>">
          <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
          <button class="submit" type="submit">Save Changes</button>
        </div>
    </form>
<?php
    drawFooter();
?>