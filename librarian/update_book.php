<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

require "../lib/db_connect_original.php";
$db = new Database();

$bookId = isset($_GET['BookID']) ? $_GET['BookID'] : null;
$bookData = null;

if ($bookId) {
    $query = $db->conn->prepare("SELECT * FROM book WHERE BookID = ?");
    $query->bind_param('i', $bookId);
    $query->execute();
    $result = $query->get_result();
    $bookData = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    // Retrieve the book ID from the GET parameter
    $bookId = isset($_GET['BookID']) ? $_GET['BookID'] : null;

    // Retrieve form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $language = $_POST['language'];
    $category = $_POST['category'];

    // Prepare an UPDATE statement to update the book details
    $updateQuery = "UPDATE book SET BookTitle = ?, Author = ?, Publisher = ?, Language = ?, Category = ? WHERE BookID = ?";
    $stmt = $db->conn->prepare($updateQuery);

    // Check if the statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param('sssssi', $title, $author, $publisher, $language, $category, $bookId);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            // echo "Book updated successfully.";

            header('Location: update_success.php');
            exit;
        } else {
            header('Location: update_error.php');
            exit;
        }

        $stmt->close();
    } else {
        echo "Database error: Unable to prepare query.";
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Book</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css">


</head>

<body>
    <?php require "header_librarian.php"; ?>


    <?php if ($bookData) : ?>
        <form class="cd-form" action="update_book.php?BookID=<?php echo htmlspecialchars($bookId); ?>" method="POST">
            <legend>Update book </legend>
            <label for="title">Book Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($bookData['BookTitle']); ?>" required>

            <label for="author">Author:</label>
            <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($bookData['Author']); ?>" required>

            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" id="publisher" value="<?php echo htmlspecialchars($bookData['Publisher']); ?>" required>

            <label for="language">Language:</label>
            <select name="language" id="language" required>
                <option value="English" <?php echo $bookData['Language'] == 'English' ? 'selected' : ''; ?>>English</option>
                <option value="French" <?php echo $bookData['Language'] == 'French' ? 'selected' : ''; ?>>French</option>
                <option value="German" <?php echo $bookData['Language'] == 'German' ? 'selected' : ''; ?>>German</option>
                <option value="Mandarin" <?php echo $bookData['Language'] == 'Mandarin' ? 'selected' : ''; ?>>Mandarin</option>
                <option value="Japanese" <?php echo $bookData['Language'] == 'Japanese' ? 'selected' : ''; ?>>Japanese</option>
                <option value="Russian" <?php echo $bookData['Language'] == 'Russian' ? 'selected' : ''; ?>>Russian</option>
                <option value="Other" <?php echo $bookData['Language'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="Fiction" <?php echo $bookData['Category'] == 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                <option value="Nonfiction" <?php echo $bookData['Category'] == 'Nonfiction' ? 'selected' : ''; ?>>Nonfiction</option>
                <option value="Reference" <?php echo $bookData['Category'] == 'Reference' ? 'selected' : ''; ?>>Reference</option>
            </select>



            <input type="submit" name="update_book" value="Update Book">
        </form>
    <?php else : ?>
        <p>Book not found.</p>
    <?php endif; ?>

    <?php require "footer_librarian.php"; ?>
</body>

</html>