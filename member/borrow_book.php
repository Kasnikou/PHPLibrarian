<?php
// borrow_book.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

require "../lib/db_connect_original.php";
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id'], $_POST['book_id'])) {
        $memberId = $_SESSION['user_id']; // User's session ID
        $bookId = $_POST['book_id'];

        // Debug: Output MemberID and BookID
        echo "Debug: MemberID is $memberId, BookID is $bookId<br>";

        // Calculate ReturnDueDate
        $borrowedDate = new DateTime(); // Today
        $returnDueDate = $borrowedDate->add(new DateInterval('P21D'))->format('Y-m-d');

        // Prepare the insert statement
        $insertQuery = "INSERT INTO bookstatus (BookID, MemberID, Status, AppliedDate) VALUES (?, ?, 'On loan', NOW())";
        if ($stmt = $db->conn->prepare($insertQuery)) {
            $stmt->bind_param('ii', $bookId, $memberId);
            if ($stmt->execute()) {
                // Check affected rows
                if ($stmt->affected_rows > 0) {
                    echo "Debug: Record successfully inserted<br>";
                    header('Location: borrow_success.php'); // Redirect to a success page
                } else {
                    echo "Debug: No record inserted<br>";
                    header('Location: borrow_error.php'); // Redirect to an error page
                }
            } else {
                echo "Debug: Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br>";
            }
        } else {
            echo "Debug: Prepare failed: (" . $db->conn->errno . ") " . $db->conn->error . "<br>";
        }
    } else {
        echo "Debug: Session member_id or POST book_id not set<br>";
    }
} else {
    echo "Debug: Incorrect request method<br>";
    header('Location: dashboard.php');
}
