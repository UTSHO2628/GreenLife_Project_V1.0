<?php
// GreenLife - Smart Environmental Impact Dashboard
//
// FILE: config/db.php
//
// This file contains the database configuration and connection logic.
// It's included by other PHP scripts that need to interact with the database.
//
// HOW TO USE:
// 1. Update the DB_SERVER, DB_USERNAME, DB_PASSWORD, and DB_NAME constants
//    with your local MySQL server details.
// 2. The default values are set for a standard XAMPP installation.

// --- DATABASE CONFIGURATION ---
// Replace these values with your actual MySQL database credentials.

// The server where the database is hosted (usually 'localhost' for XAMPP).
define('DB_SERVER', 'localhost');

// Your MySQL username (usually 'root' for a default XAMPP installation).
define('DB_USERNAME', 'root');

// Your MySQL password (usually empty for a default XAMPP installation).
define('DB_PASSWORD', '');

// The name of the database created for this project.
define('DB_NAME', 'greenlife_db');


// --- ESTABLISH DATABASE CONNECTION ---

// The $mysqli object will be used for all database interactions.
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the connection to the database was successful.
// If it fails, the script will terminate and display an error message.
// This is a crucial step for debugging connection issues.
if ($mysqli->connect_error) {
    // The die() function stops script execution immediately.
    die("ERROR: Could not connect to the database. " . $mysqli->connect_error);
}

// Optional: Set the character set to utf8mb4 to support a wide range of characters.
$mysqli->set_charset('utf8mb4');

?>
