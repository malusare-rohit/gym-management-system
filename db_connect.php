<?php
// Database connection parameters
$host = "localhost";        // Change if needed
$dbname = "gym_management"; // Your database name
$user = "postgres";         // PostgreSQL username
$password = "root";         // PostgreSQL password

// Create a connection using PDO
try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
