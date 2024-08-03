<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "lib/db_connect_original.php"; // Adjust this path as needed
$error = ''; // Initialize an error message variable

if (isset($_POST['m_register'])) {
    $m_user = $_POST['m_user'] ?? '';
    $l_name = $_POST['l_name'] ?? '';
    $m_email = $_POST['m_email'] ?? '';
    $m_pass = $_POST['m_pass'] ?? '';
    $m_pass_confirm = $_POST['m_pass_confirm'] ?? '';

    // Server-side validation
    if (!preg_match("/^[a-zA-Z ]{1,20}$/", $m_user)) {
        $error = "Invalid name. Names should only contain letters and be no more than 20 characters.";
    } elseif (!filter_var($m_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($m_pass !== $m_pass_confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        if (empty($error)) {
            $userCheckQuery = $db->conn->prepare("SELECT FirstName FROM user WHERE FirstName = ?");
            $userCheckQuery->bind_param("s", $m_user);
            $userCheckQuery->execute();
            $userCheckResult = $userCheckQuery->get_result();
            if ($userCheckResult->num_rows > 0) {
                $error = "Username is already taken.";
            }
        }

        // Check if email already exists
        if (empty($error)) {
            $emailCheckQuery = $db->conn->prepare("SELECT Email FROM user WHERE Email = ?");
            $emailCheckQuery->bind_param("s", $m_email);
            $emailCheckQuery->execute();
            $emailCheckResult = $emailCheckQuery->get_result();
            if ($emailCheckResult->num_rows > 0) {
                $error = "Email address is already associated with an account.";
            }
        }

        // Proceed with registration if no errors
        if (empty($error)) {
            $hashed_password = password_hash($m_pass, PASSWORD_DEFAULT);
            $insertQuery = $db->conn->prepare("INSERT INTO user (FirstName, LastName, Email, Password, MemberType) VALUES (?, ?, ?, ?, 'member')");
            $insertQuery->bind_param("ssss", $m_user, $l_name, $m_email, $hashed_password);
            if (!$insertQuery->execute()) {
                $error = "Registration failed due to a database error.";
            } else {
                header('Location: login.php?type=member&registered=1');
                exit;
            }
        }
    }
}
?>

<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="css/form_styles.css">
    <link rel="stylesheet" href="css/register_style.css">
</head>

<body>
    <?php require "member/header_member.php"; ?>
    <?php if (!empty($error)) : ?>
        <div class="error-message" id="error-message">
            <p id='error'><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <form class="cd-form" method="POST" action="register.php" name="registrationForm" onsubmit="return validateRegistrationForm()">
        <legend>Register</legend>



        <div class="icon">
            <input class="m-user" type="text" name="m_user" placeholder="First Name" required />
        </div>

        <div class="icon">
            <input class="m-user" type="text" name="l_name" placeholder="Last Name" required />
        </div>

        <div class="icon">
            <input class="m-email" type="email" name="m_email" placeholder="Email Address" required />
        </div>

        <div class="icon">
            <input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
        </div>

        <div class="icon">
            <input class="m-pass" type="password" name="m_pass_confirm" placeholder="Re-type Password" required />
        </div>

        <input type="submit" name="m_register" value="Register" />
    </form>

    <?php require "member/footer_member.php"; ?>
    <script src="/js/registration_validation.js"></script>
</body>

</html>
``