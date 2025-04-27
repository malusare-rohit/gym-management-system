<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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


        .container {
            background-color: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
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

        .footer {
            margin-top: 20px;
        }

        .footer a {
            text-decoration: none;
            color: #007bff;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 400px) {
            .container {
                width: 90%;
            }
        }

        .hidden {
            display: none;
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

    <div class="container">
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include 'db_connect.php';

    if (!$conn) {
        die("Database connection failed!");
    }

    // Get form values and sanitize
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $role = 'user'; // Default role

    try {
        // Check if email already exists
        $checkEmail = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $conn->prepare($checkEmail);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists > 0) {
            echo "<p style='color:red;'>This email is already registered. Please use a different email or <a href='./login.php'>login</a>.</p>";
        } else {
            // Insert data into the database with correct column names
            $sql = "INSERT INTO users (\"first_name\", \"last_name\", \"email\", \"password\", \"phone_number\", \"role\") 
                    VALUES (:first_name, :last_name, :email, :password, :phone_number, :role)";
            
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Storing hashed password
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':role', $role);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<h2>Welcome, $firstName!</h2>";
                echo "<p>Thank you for registering. You can now <a href='./index.html'>go to the homepage</a> or <a href='./login.php'>login</a> with your email and password.</p>";
            } else {
                echo "<p>Registration failed. Please try again.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}else {
        ?>
<!-- Register from-->
            <h2>Register</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="email">Phone No.</label>
                    <input type="tel" id="phone_number" name="phone_number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email ID</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <div class="footer">
                <p><a href="./login.php">Already have an account?</a></p>
                <p><a href="./admin_login.php">Admin Login</a></p>
            </div>
        <?php
        }
        ?>
    </div>
</body>
</html>