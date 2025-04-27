<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $plan_id = $_POST['plan_id'];

    // Check if the user already has a membership
    $stmt = $conn->prepare("SELECT * FROM user_memberships WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $existingMembership = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingMembership) {
        echo "You are already a member!";
        exit();
    }

    // Get plan details
    $stmt = $conn->prepare("SELECT duration FROM membership_plans WHERE plan_id = :plan_id");
    $stmt->bindParam(':plan_id', $plan_id);
    $stmt->execute();
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plan) {
        echo "Invalid plan selected!";
        exit();
    }

    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d", strtotime("+{$plan['duration']} months"));
    $payment_status = "Pending"; // Change this after payment integration

    // Insert new membership
    $stmt = $conn->prepare("INSERT INTO user_memberships (user_id, plan_id, start_date, end_date, payment_status) 
                            VALUES (:user_id, :plan_id, :start_date, :end_date, :payment_status)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':plan_id', $plan_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':payment_status', $payment_status);

    if ($stmt->execute()) {
        header("Location: user_dashboard.php"); // Redirect after successful joining
        exit();
    } else {
        echo "Error joining membership!";
    }
}
?>
