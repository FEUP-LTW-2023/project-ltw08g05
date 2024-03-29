<?php
    require_once (__DIR__ . '/../templates/Tcommon.php'); // session is declared in Tcommon.php
    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Musers.php');
    require_once("../../src/controllers/LoginController.php");
    
    if (!isset($_SESSION['email'])) {
      header('Location: login.php');
      exit();
    }
    $db = getDatabaseConnection();
    if ($db == null){
        throw new Exception('Database not initialized');
        die("$db is null");
    }

    $email = $_SESSION['email'];
    $current_user = User::getUserByEmail($db, $email);
    if(!$current_user){
        error_log("profile: user not found");
        ?> <p> <?php echo "User was not found" ?></p> <?php
        header('Location: /src/controllers/logout.php');
        exit();
    }

    $username = $current_user->getUsername();
    drawHeader($db);
?>
    <head>
    <link href="../styles/profile.css" rel="stylesheet">
    </head>
    
    <?php if(isset($_SESSION['error_message'])){
        $error = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    ?> <p class="error-message"> <?php echo $error ?></p> <?php } ?>

    <h3>User <?php echo $username?></h3><br><br>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Full Name</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getFullName() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getEmail() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Address</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getAddress() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Country</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getCountry() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">City</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getCity() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Zip Code</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getzip_code() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Bio</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getBio() ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">is Agent</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php if ($current_user->getIsAgent() === 1) echo "yes"; else echo "no"; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">is Admin</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php if ($current_user->getIsAdmin() === 1) echo "yes"; else echo "no"; ?></p>
              </div>
            </div>
            <hr>
          </div>
          <div class="buttons">
          <form action="edit_profile.php" method="get"> 
              <input type="hidden" name="user" value=<?php echo urlencode($username) ?>>
              <button type="submit">Edit Profile</button>    
          </form>
          
          <form action="change_password.php" method="post"> 
              <input type="hidden" name="user" value=<?php echo urlencode($username) ?>>
              <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
              <button type="submit">Change Password</button>    
          </form>

          <form action="access_log.php" method="get"> 
              <input type="hidden" name="user" value=<?php echo urlencode($username) ?>>
              <button type="submit">View Login History</button>    
          </form>
          </div>
    </div>  
<?php
    drawFooter();
?>
