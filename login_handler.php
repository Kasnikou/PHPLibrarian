<?php
// login_handler.php
session_start();
require "lib/db_connect_original.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Debug: Show username received
        echo "Debug - Username received: " . htmlspecialchars($username) . "<br>";

        $query = $db->conn->prepare("SELECT MemberID, MemberType, Password FROM user WHERE Email = ?");
        if (!$query) {
            echo "Database error: Unable to prepare query.";
            exit;
        }

        $query->bind_param("s", $username);
        if (!$query->execute()) {
            echo "Database error: Unable to execute query.";
            exit;
        }

        $result = $query->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $result->free();

            // Debug: Show if password is about to be verified
            echo "Debug - About to verify password.<br>";

            if (!password_verify($password, $user['Password'])) {
                echo "Debug - Password verification failed.<br>";
                $_SESSION['login_errors'] = ["Invalid username or password."];
                header("Location: login.php");
                exit;
            }

            echo "Password verified. Redirecting to dashboard...<br>";

            // Set session variables
            $_SESSION['user_id'] = $user['MemberID'];

            echo "Debug - Session user_id set to: " . $_SESSION['user_id'] . "<br>";
            $_SESSION['role'] = $user['MemberType'];

            // Redirect based on MemberType
            if ($user['MemberType'] === 'Member') {
                echo "Redirecting to Member dashboard.<br>";
                header("Location: member/dashboard.php");
                exit;
            } elseif ($user['MemberType'] === 'Admin') {
                echo "Redirecting to Admin dashboard.<br>";
                header("Location: librarian/dashboard.php");
                exit;
            } else {
                echo "Unknown user role.<br>";
                $_SESSION['login_errors'] = ["Unknown user role."];
                header("Location: login.php");
                exit;
            }
        } else {
            echo "No user found with the entered username: " . htmlspecialchars($username) . "<br>";
            $_SESSION['login_errors'] = ["No user found with the entered username."];
            header("Location: login.php");
            exit;
        }
        $query->close();
    } else {
        echo "Form not filled correctly.<br>";
        $_SESSION['login_errors'] = ['Form not filled correctly'];
        header("Location: login.php");
        exit;
    }
} else {
    echo "Accessed without POST data.<br>";
    header("Location: login.php");
    exit;
}
