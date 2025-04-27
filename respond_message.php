<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message_id = $_POST['message_id'];
    $response = $_POST['response'];

    $stmt = $conn->prepare("UPDATE user_messages SET response = :response, status = 'Responded' WHERE message_id = :message_id");
    $stmt->bindParam(':response', $response);
    $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: admin_dashboard.php");
exit();
?>
