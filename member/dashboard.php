<?php
session_start();
if (!isset($_SESSION['user_id'])) {

    header("Location: ../index.php");

    exit;
}
// echo "Debug - Session user_id set to: " . $_SESSION['user_id'] . "<br>";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../lib/db_connect_original.php"; // Adjust the path as necessary

$db = new Database();
$query = "SELECT book.*, bookstatus.Status, bookstatus.AppliedDate FROM book LEFT JOIN bookstatus ON book.BookID = bookstatus.BookID";
$result = $db->conn->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Member Login</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../member/css/home_style.css">
</head>

<body>
    <?php require "../member/header_member.php";
    ?>
    <main>
        <section class="my-books">
            <div class="my-books">
                <h2>My Books</h2>
            </div>

            <div class='book-container'>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imagePath = "../book_covers/" . htmlspecialchars($row['cover_image_path']);
                        $imageSrc = file_exists($imagePath) ? $imagePath : '../book_covers/default-cover.png';

                        echo "<div class='book-card'>";
                        echo "<img src='" . $imageSrc . "' alt='Book Cover' class='book-cover'>";
                        echo "<div class='book-info'>";
                        echo "<h3 class='book-title'>" . ucwords(htmlspecialchars($row['BookTitle'])) . "</h3>";
                        echo "<p class='book-author'>" . ucwords(htmlspecialchars($row['Author'])) . "</p>";
                        echo "<p class='book-publisher'>" . ucwords(htmlspecialchars($row['Publisher'])) . "</p>";
                        echo "<p class='book-category'>" . htmlspecialchars($row['Category']) . "</p>";
                        echo "<p class='book-language'>" . htmlspecialchars($row['Language']) . "</p>";

                        // Check if 'Status' column is fetched from the database and set the variable
                        $status = isset($row['Status']) ? $row['Status'] : 'Not set';



                        // Query to check the status of the book
                        $statusQuery = "SELECT Status FROM bookstatus WHERE BookID = ?";
                        $statusStmt = $db->conn->prepare($statusQuery);
                        $statusStmt->bind_param('i', $row['BookID']);
                        $statusStmt->execute();
                        $statusResult = $statusStmt->get_result();

                        $bookStatus = 'Available'; // Default status
                        if ($statusRow = $statusResult->fetch_assoc()) {
                            $bookStatus = $statusRow['Status'];
                        }
                        echo "<p class='book-status'>" . htmlspecialchars($bookStatus) . "</p>";


                        if ($bookStatus == 'Available') { // Show borrow form only if available
                            echo "<form method='POST' action='borrow_book.php'>";

                            echo "<input type='hidden' name='book_id' value='" . htmlspecialchars($row['BookID']) . "'>";

                            echo "<input type='submit' name='borrow_book' value='Borrow' class='borrow-button'>";

                            echo "</form>";
                        } else { // Show status message if not available
                            echo "<p>Not available for loan</p>";
                            // echo "<p class='book-status'>" . htmlspecialchars($status) . "</p>";
                        }

                        // Borrow button
                        echo "<form method='POST' action='borrow_book.php'>";

                        echo "</form>";
                        echo "</div>"; // Close book-info
                        echo "</div>"; // Close book-card
                    }
                } else {
                    echo "No books available";
                }
                ?>
            </div>
        </section>
    </main>
    <?php require "../member/footer_member.php"; ?>
</body>

</html>