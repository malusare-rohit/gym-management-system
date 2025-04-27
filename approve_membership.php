<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['membership_id'])) {
    $membership_id = $_GET['membership_id'];

    $stmt = $conn->prepare("UPDATE user_memberships SET payment_status = 'Paid' WHERE membership_id = :membership_id");
    $stmt->bindParam(':membership_id', $membership_id, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: admin_dashboard.php");
exit();
?>
