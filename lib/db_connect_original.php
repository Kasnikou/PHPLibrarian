<?php

class Database
{
    private $host = 'localhost';
    private $username = 'root'; // For local development only. Use environment variables for production.
    private $password = ''; // For local development only. Set a password for production.
    private $database = 'au_library';

    public $conn;

    public function __construct()
    {
        // Automatically calls the connect function upon instantiation
        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            // Log this error to a file or send an email to the admin instead of displaying it
            error_log('Connection failed: ' . $this->conn->connect_error);
            die("Connection failed. Please contact the administrator.");
        }

        // Optionally unset the username and password for security reasons
        unset($this->username);
        unset($this->password);
    }

    // ... Other methods ...

    // Explicitly close the connection if needed
    public function close()
    {
        $this->conn->close();
    }
}

// Instantiate the Database class
$db = new Database();
