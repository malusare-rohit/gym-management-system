<?php 
session_start();
require_once 'db_connect.php'; // Secure database connection

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT user_id, first_name, last_name, password, role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation attacks
            $_SESSION['role'] = $user['role']; // Add this
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = htmlspecialchars($user['first_name'] . " " . $user['last_name']);

            header("Location: user_dashboard.php");
            exit();
        } else {
            $error_message = '❌ Invalid email or password.';
        }
    } catch (PDOException $e) {
        $error_message = "⚠️ Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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


        .login-container {
            background: white;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
     <!-- 🔹 Navigation Bar -->
     <div class="navbar">
        <div class="logo">🏋️‍♂️ Gym Management</div>
        <div>
            <a href="./index.html">Home</a>
            <a href="membership.php">Membership</a>
            <a href="workout_plans.php">Workout Plans</a>
            <a href="diet_plans.php">Diet Plans</a>
        </div>
    </div>

    <div class="login-container">
        <h2>User Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="error-message">
            <?php if (!empty($error_message)) echo $error_message; ?>
        </div>
        <a href="./admin_login.php">Admin Login</a>
    </div>
</body>
</html>
