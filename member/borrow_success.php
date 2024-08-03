<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Borrow Successful</title>
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
    <?php require "../member/header_member.php"; ?>
    <main>
        <section class="borrow-status">
            <h1>Success</h1>
            <p>The book has been successfully borrowed.</p>
            <p><a href="dashboard.php">Go back to the dashboard</a></p>
        </section>
    </main>
    <?php require "../member/footer_member.php"; ?>
</body>

</html>