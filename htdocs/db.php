<?php
// Set up your database connection parameters
$servername = "sql111.infinityfree.com"; // Your MySQL Hostname
$username = "if0_37869275";             // Your MySQL Username
$password = "PUg6VUe96570Q";            // Your MySQL Password
$dbname = "if0_37869275_aidalert";           // Your MySQL Database Name

// Create the MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
