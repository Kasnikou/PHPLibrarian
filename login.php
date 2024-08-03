<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize the success message variable
$success_message = '';

// Start session with a 2-hour limit
ini_set('session.gc_maxlifetime', 7200); // Set session lifetime to 2 hours (7200 seconds)
session_start();

require "inc/message_display.php";


if (isset($_GET['registered']) && $_GET['registered'] == '1') {
    $success_message = "Registration successful. Please log in.";
}

$userType = isset($_GET['type']) && $_GET['type'] === 'librarian' ? 'librarian' : 'member';
$loginAction = "login_handler.php";
if (isset($_SESSION['registration_success'])) {
    echo "<p class='success-message'>" . $_SESSION['registration_success'] . "</p>";
    unset($_SESSION['registration_success']);
}


?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo ucfirst($userType); ?> Login</title>
    <link rel="stylesheet" type="text/css" href="css//global_styles.css">
    <link rel="stylesheet" type="text/css" href="css/form_styles.css">
    <link rel="stylesheet" type="text/css" href="css/index_style.css">
</head>

<body>
    <!-- Navigation -->
    <?php include("inc/header.php"); ?>

    <!-- Display success message if any -->
    <?php if ($success_message != '') : ?>
        <div class="success-message">
            <p><?php echo htmlspecialchars($success_message); ?></p>
        </div>
    <?php endif; ?>

    <!-- Login Form -->

    <?php if (!empty($_SESSION['login_errors'])) : ?>
        <div class="error-message">
            <?php foreach ($_SESSION['login_errors'] as $error) : ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['login_errors']); // Clear the errors after displaying 
        ?>
    <?php endif; ?>
    <form class="cd-form" method="POST" action="<?php echo $loginAction; ?>">
        <legend><?php echo ucfirst($userType); ?> Login</legend>
        <div class="error-message" id="error-message">
            <?php if (isset($error) && $error != '') : ?>
                <p id="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </div>
        <div class="icon">
            <input class="m-user" type="text" name="username" placeholder="Username" required />
        </div>
        <div class="icon">
            <input class="m-pass" type="password" name="password" placeholder="Password" required />
        </div>
        <input type="submit" value="Login" name="login" />
        <?php if ($userType === 'member') : ?>
            <br /><br /><br /><br />
            <p align="center">Don't have an account?&nbsp;<a href="register.php">Sign up</a></p>
        <?php endif; ?>
    </form>

    <!-- Footer -->
    <?php include("inc/footer.php"); ?>
    <script src="js/form_validation.js"></script>
</body>

</html>