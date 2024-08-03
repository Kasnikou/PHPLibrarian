<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "lib/db_connect_original.php";


// if (!empty($_SESSION['type'])) {
//     if (strcmp($_SESSION['type'], "librarian") == 0) {
//         header("Location: librarian/dashboard.php");
//         exit;
//     } else if (strcmp($_SESSION['type'], "member") == 0) {
//         header("Location: member/dashboard.php");
//         exit;
//     }
// }
// Only now do we include 'header.php' because it might contain HTML.

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" type="text/css" href="css/index_style.css" />
    <style>
        .hover-link:hover {
            transform: scale(1.2);

        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <?php include("inc/header.php"); ?>
    <!-- <?php include("inc/nav.php"); ?> -->


    <!-- Main Content -->
    <main id="allTheThings">
        <div id="member">
            <a href="login.php">
                <img src="img/ic_member.svg" width="250px" height="auto" /><br />
                Login
            </a>
        </div>
        <div id="verticalLine"></div>
        <div id="librarian">
            <a href="register.php">
                <img src="img/ic_librarian.svg" width="250px" height="auto" /><br />
                Register
            </a>
        </div>
        <!-- <div class="hover-link"><a href="register.php">Register here</a></div> -->
    </main>


    <!-- Footer -->

    <?php include("inc/footer.php"); ?>


</body>

</html>