<?php
    require_once (__DIR__ . '/../templates/Tcommon.php');
    require_once("../../src/controllers/RegisterController.php");
    require_once("../../src/controllers/LoginController.php");
    session_start();
    
    drawHeader();
?>

    <head>
    <link href="../styles/register.css" rel="stylesheet">
    <script src="../scripts/register.js"></script>
    
    </head>

    <!-- <?php LoginController::showRecordsFromDatabase() ?> -->

    <div class="centered">
      <h2>Register</h2>
    <form>
    <label for="email">Email: <span class="required">*</span></label>
    <input type="email" name="email" id="email" required
        <?php if (isset($_SESSION['email'])) echo 'value="' . $_SESSION['email'] . '"'; ?>>
    <br>
    <span id="email-error" class="error"></span>

    <br>

    <label for="password">Password: <span class="required">*</span></label>
    <input type="password" name="password" id="password" required>
    <br>

    <label for="firstName">First Name: <span class="required">*</span></label>
    <input type="text" name="first_name" id="first_name" required>
    <br>

    <label for="lastName">Last Name: <span class="required">*</span></label>
    <input type="text" name="last_name" id="last_mame" required>
    <br>

    <label for="username">Username:</label>
    <input type="text" name="username" id="username">
    <br>

    <label for="address">Address: <span class="required">*</span></label>
    <input type="text" name="address" id="address" required>
    <br>

    <label for="country">Country:</label>
<select name="country" id="country">
  <option value="" selected disabled>Select a country</option>
  <?php
    $countries = array(
      'United States',
      'Canada',
      'Mexico',
      'Brazil',
      'Argentina',
      'United Kingdom',
      'France',
      'Germany',
      'Spain',
      'Italy',
      'Netherlands',
      'Belgium',
      'Switzerland',
      'Austria',
      'Sweden'
    );

    foreach ($countries as $country) {
      echo '<option value="' . $country . '">' . $country . '</option>';
    }
  ?>
</select>

    <br>

    <label for="city">City:</label>
    <input type="text" name="city" id="city">
    <br>

    <label for="zipCode">Zip Code:</label>
    <input type="text" name="zipCode" id="zipCode">
    <br>

    <label for="bio">Bio:</label>
    <textarea name="bio" id="bio"></textarea>
    <br>

    <label for="isAgent">Register as agent:</label>
    <input type="checkbox" name="isAgent" id="isAgent">
    <br>

    <label for="isAdmin">Register as admin:</label>
    <input type="checkbox" name="isAdmin" id="isAdmin">
    <br>

    <button formaction="/src/controllers/RegisterController.php" formmethod="post" disabled hover-text="Fields are not filled" style="vertical-align:middle"><span>Login</span></button>
    </form>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <?php
    drawFooter();
?>
