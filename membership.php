<?php
session_start();
include 'db_connect.php';

$stmt = $conn->prepare("SELECT * FROM membership_plans");
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Membership</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #333;
            margin-bottom: 15px;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        select:hover, select:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            background: #007bff;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Choose a Membership Plan</h2>
        <form action="join_membership.php" method="POST">
            <select name="plan_id" required>
                <option value="" disabled selected>Select a Plan</option>
                <?php foreach ($plans as $plan): ?>
                    <option value="<?php echo $plan['plan_id']; ?>">
                        <?php echo $plan['plan_name'] . " - ₹" . $plan['price'] . " (". $plan['duration'] ." months)"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Join Now</button>
        </form>
    </div>
</body>
</html>
