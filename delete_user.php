<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    try {
        // Start transaction to ensure atomicity
        $conn->beginTransaction();

        // 🔹 Step 1: Delete related records in user_memberships
        $stmt1 = $conn->prepare("DELETE FROM user_memberships WHERE user_id = :user_id");
        $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt1->execute();

        // 🔹 Step 2: Now delete the user
        $stmt2 = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt2->execute();

        // Commit transaction if everything is successful
        $conn->commit();

        // Redirect back to the admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $conn->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
