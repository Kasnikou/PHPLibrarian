<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Include the database connection file
    require 'lib/db_connect.php';

    // A simple test query
    $testQuery = "SELECT 1;";

    // Execute the query
    $result = $con->query($testQuery);

    // Check if the query was successful
    if ($result) {
        echo "Database connection successful.";
    } else {
        echo "Database connection failed: " . $con->error;
    }
?>
