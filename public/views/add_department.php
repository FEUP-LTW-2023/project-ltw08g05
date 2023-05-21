<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../../database/connection.php');
require_once('../../src/models/Musers.php');
require_once('../templates/Tcommon.php'); // session_start() is declared here




function drawAddDepartment($db) {
    $agents = User::getAgents($db);
    ?>
    <section class="form-container">
      <h1>New Department</h1>
      <form action="../../src/controllers/action_create_department.php" method="post">
          <input type="text" name="title" placeholder="Department's Name"><br>
          
          <section class="radio-group">
            <?php foreach($agents as $agent) { ?> 
              <label class="radio-label">
                <input type="radio" name="agent" value="<?= $agent->getUserID() ?>">
                <?= $agent->getfirst_name() ?> <?= $agent->getlast_name() ?>
                <span class="radio-button"></span>
              </label>
            <?php } ?>
          </section>
          <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
          <button type="submit">Submit</button>
      </form>
      <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
    </section>
<?php }


// connect to database
try {
    $db = getDatabaseConnection();
    drawHeader($db);
    drawAddDepartment($db);
    drawFooter();
} catch (PDOException $e) {
    echo "Oops, we've got a problem related to database connection:";
    die("Error connecting to database: " . $e->getMessage());
}

?> 

