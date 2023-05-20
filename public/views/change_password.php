<?php
require_once (__DIR__ . '/../templates/Tcommon.php'); // session is declared in Tcommon.php
require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Musers.php');
require_once(__DIR__ . '/../../database/connection.php');

if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
    die('CSRF token verification failed!');
}

if (!isset($_POST['user'])) {
    die("You don't have enough permissions.");
}

if (!isset($_SESSION['email'])) {
    header('Location: src/controllers/logout.php');
    exit();
}

$db = getDatabaseConnection();
if ($db == null) {
    throw new Exception('Database not initialized');
    echo "Oops, we've got a problem related to the database connection:";
    die("Error connecting to the database: " . $e->getMessage());
}


$email = $_SESSION['email'];

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
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8')?>">
          <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
          <button class="submit" type="submit">Save Changes</button>
        </div>
    </form>
<?php
drawFooter();
?>
