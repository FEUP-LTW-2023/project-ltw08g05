<?php
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
    error_log("profile email: $email");
    $current_user = User::getUserByEmail($db, $email);
    if(!$current_user){
        error_log("profile: user not found");
        ?> <p> <?php echo "User was not found" ?></p> <?php
        header('Location: /src/controllers/logout.php');
        exit();
    }
    $username = $current_user->getUsername();

    drawHeader()
?>
    <head>
    <link href="../styles/profile.css" rel="stylesheet">
    </head>
    
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
          </div>
          <!-- using method GET because we are not changing anything in the database -->
          <form action="edit_profile.php" method="get"> 
            <input type="hidden" name="user" value=<?php echo $username?>>
              <button type="submit">Edit Ticket</button>    
        </form>
        </div>  

<?php
    drawFooter();
?>
