<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Borrow Error</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <!-- Add any additional CSS files you need -->
</head>
<style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<body>
    <?php require "header_librarian.php"; ?>
    <main>
        <section class="borrow-status">
            <h1>Error</h1>
            <p>There was a problem delete the book. Please try again or contact support if the problem persists.</p>
            <p><a href="dashboard.php">Go back to the dashboard</a></p>
        </section>
    </main>
    <?php require "footer_librarian.php"; ?>
</body>

</html>