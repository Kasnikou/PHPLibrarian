<?php
// return_book.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Debug - User is not logged in.<br>";
    header("Location: ../index.php");
    exit;
}

require "../lib/db_connect_original.php"; // Adjust the path as necessary
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['BookID'])) {

        $bookId = $_POST['BookID'];

        echo "Debug - Processing return for book ID: $bookId";

        // Prepare the delete statement to remove the record of the loaned book
        $deleteQuery = "DELETE FROM bookstatus WHERE BookID = ? AND Status = 'On loan'";


        if ($stmt = $db->conn->prepare($deleteQuery)) {
            $stmt->bind_param('i', $bookId);
            if ($stmt->execute()) {
                // Redirect based on the outcome
                if ($stmt->affected_rows == 1) {
                    // echo "Debug - Book return processed successfully.<br>";
                    header('Location: return_success.php');
                } else {
                    // echo "Debug - No rows affected. Possible reasons: book not on loan or does not exist.<br>";
                    header('Location: return_error.php'); // Redirect to an error page
                }
            } else {
                echo "Debug - Failed to execute the delete statement. Error: " . $stmt->error . "<br>";
            }
        }
    }
}
