<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT m.plan_name, u.start_date, u.end_date, u.payment_status 
                        FROM user_memberships u 
                        JOIN membership_plans m ON u.plan_id = m.plan_id 
                        WHERE u.user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$membership = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        /* 🔹 Navigation Bar */
        .navbar {
            background: #007bff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }
        .navbar .logout {
            background: #ff4d4d;
            padding: 8px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .navbar .logout:hover {
            background: #cc0000;
        }

        /* 🔹 Dashboard Container */
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
            margin: 100px auto; /* Adjusted for navbar */
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .pending {
            color: red;
            background: #ffd1d1;
        }
        .paid {
            color: green;
            background: #d1ffd1;
        }
        a.button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            border-radius: 5px;
            transition: 0.3s;
        }
        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <!-- 🔹 Navigation Bar -->
    <div class="navbar">
        <div class="logo">🏋️‍♂️ Gym Management</div>
        <div>
            <a href="#">Dashboard</a>
            <a href="#">Membership</a>
            <a href="#">Workout Plans</a>
            <a href="#">Diet Plans</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- 🔹 User Dashboard -->
    <div class="container">
        <h2>Welcome, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "User"; ?>!</h2>

        <?php if ($membership): ?>
            <p><strong>Membership Plan:</strong> <?php echo $membership['plan_name']; ?></p>
            <p><strong>Valid From:</strong> <?php echo $membership['start_date']; ?></p>
            <p><strong>Valid Till:</strong> <?php echo $membership['end_date']; ?></p>
            <p><strong>Payment Status:</strong> 
                <span class="status <?php echo ($membership['payment_status'] == 'Pending') ? 'pending' : 'paid'; ?>">
                    <?php echo $membership['payment_status']; ?>
                </span>
            </p>
            <a href="renew_membership.php" class="button">Renew Membership</a>
        <?php else: ?>
            <p>You are not a member yet.</p>
            <a href="membership.php" class="button">Join Membership</a>
        <?php endif; ?>
    </div>

</body>
</html>
