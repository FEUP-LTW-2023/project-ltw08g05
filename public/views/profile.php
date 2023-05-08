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
    $user = new User(123, "John Doe", "john.doe@example.com");
    $email = $_SESSION['email'];
    $current_user = User::getUserByEmail($db, $email);
    

    drawHeader()
?>
    <body>
        <h3 class="centered">User <?php echo $email?></h3><br><br> 
        <div class="card mb-4">
        <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Full Name</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $current_user->getName() ?></p>
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
                <p class="mb-0">Phone</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">(097) 234-5678</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Mobile</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">(098) 765-4321</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Address</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
              </div>
            </div>
          </div>


    </body>
<?php
    drawFooter();
?>
