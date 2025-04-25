<?php 

// Define database credentials
define("DB_USER", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'barangay');
define("DB_HOST", 'localhost');

// Create a database connection
try {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}

?>
