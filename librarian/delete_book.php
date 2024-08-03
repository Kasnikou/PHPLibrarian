<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

require "../lib/db_connect_original.php";
$db = new Database();

$bookId = isset($_GET['BookID']) ? $_GET['BookID'] : null;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Delete Book</title>
    <style>
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .center-form {
            width: 300px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background: #fff;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
        if ($bookId) {
            $query = $db->conn->prepare("DELETE FROM book WHERE BookID = ?");
            $query->bind_param('i', $bookId);

            if ($query->execute()) {
                header('Location: delete_success.php');
                exit;
            } else {
                header('Location: delete_error.php');
                exit;
            }
        }
    } else {
        if ($bookId) {
            echo '<div class="center-container">';
            echo '<form method="POST" class="center-form">';
            echo '<p>Are you sure you want to delete this book?</p>';
            echo '<input type="submit" name="delete_book" value="Delete Book">';
            echo '</form>';
            echo '</div>';
        }
    }
    ?>
</body>

</html>