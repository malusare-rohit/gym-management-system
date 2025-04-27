<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users
$users_stmt = $conn->prepare("SELECT user_id, first_name, last_name, email, phone_number, role FROM users");
$users_stmt->execute();
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending membership approvals
$requests_stmt = $conn->prepare("SELECT u.membership_id, u.user_id, us.first_name, us.last_name, m.plan_name, u.payment_status 
                                FROM user_memberships u
                                JOIN users us ON u.user_id = us.user_id
                                JOIN membership_plans m ON u.plan_id = m.plan_id
                                WHERE u.payment_status = 'Pending'");
$requests_stmt->execute();
$requests = $requests_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all membership details
$memberships_stmt = $conn->prepare("SELECT us.first_name, us.last_name, us.email, us.phone_number, 
                                    m.plan_name, m.price, u.start_date, u.end_date, u.payment_status 
                                    FROM user_memberships u
                                    JOIN users us ON u.user_id = us.user_id
                                    JOIN membership_plans m ON u.plan_id = m.plan_id");
$memberships_stmt->execute();
$memberships = $memberships_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pending-payment {
            background-color: #ffdddd;
            color: red;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn-edit { background: #28a745; }
        .btn-delete { background: #dc3545; }
        .btn-approve { background: #007bff; }
        .btn-reject { background: #dc3545; }
        .btn:hover {
            opacity: 0.8;
        }
        a.logout {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            background: #ff4444;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.logout:hover {
            background: #cc0000;
        }
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }
            .btn {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <a href="logout.php" class="logout">Logout</a>
        
        <h3>User Management</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['first_name'] . " " . $user['last_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['phone_number']; ?></td>
                <td><?php echo ucfirst($user['role']); ?></td>
                <td>
                    <a href="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Pending Membership Approvals</h3>
        <table>
            <tr>
                <th>User</th>
                <th>Plan</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($requests as $request): ?>
            <tr>
                <td><?php echo $request['first_name'] . " " . $request['last_name']; ?></td>
                <td><?php echo $request['plan_name']; ?></td>
                <td><?php echo $request['payment_status']; ?></td>
                <td>
                    <a href="approve_membership.php?membership_id=<?php echo $request['membership_id']; ?>" class="btn btn-approve">Approve</a>
                    <a href="reject_membership.php?membership_id=<?php echo $request['membership_id']; ?>" class="btn btn-reject">Reject</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Membership Details</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Plan Name</th>
                <th>Price</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Payment Status</th>
            </tr>
            <?php foreach ($memberships as $membership): ?>
            <tr class="<?php echo $membership['payment_status'] === 'Pending' ? 'pending-payment' : ''; ?>">
                <td><?php echo $membership['first_name'] . " " . $membership['last_name']; ?></td>
                <td><?php echo $membership['email']; ?></td>
                <td><?php echo $membership['phone_number']; ?></td>
                <td><?php echo $membership['plan_name']; ?></td>
                <td><?php echo "₹" . number_format($membership['price'], 2); ?></td>
                <td><?php echo $membership['start_date']; ?></td>
                <td><?php echo $membership['end_date']; ?></td>
                <td><?php echo $membership['payment_status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
