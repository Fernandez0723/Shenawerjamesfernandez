<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch notifications
$sql = "SELECT * FROM admin_notifications ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Delete notification action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM admin_notifications WHERE id = :id";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $delete_stmt->execute();

    header('Location: admin_notification.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Notifications</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

        h1 {
            color: #333;
            margin-top: 20px;
        }

        nav {
            background-color: #007bff;
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            flex: 1;
        }

        nav .nav-links {
            display: flex;
            justify-content: flex-end;
            flex: 3;
        }

        nav a {
            text-decoration: none;
            color: white;
            margin-right: 20px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 20px;
        }

        .notification-list {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        .notification-item {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            color: #333;
            position: relative;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        .notification-item p {
            margin: 0;
        }

        .notification-item small {
            color: #888;
            font-size: 12px;
            position: absolute;
            bottom: 10px;
            right: 15px;
        }

        .notification-actions {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 12px;
        }

        .notification-actions a {
            text-decoration: none;
            color: #007bff;
            margin-left: 10px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Print Button Styling */
        .print-btn {
            position: fixed;
            top: 40px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            z-index: 1000;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo">Barangay Rental System</div>
    </div>
    <div class="nav-links">
        <a href="../dashboard/admin_dashboard.php">Dashboard</a>
        <a href="../items/add_item.php">Add Items</a>
        <a href="../reservations/view_reservations.php">View Reservations</a>
        <a href="../reservations/payment_record.php">Payment Record</a>
        <a href="../reservations/admin_notification.php">Notification</a>
        <a href="../dashboard/manage_user.php">Users Account</a>
        <a href="../logout.php">Logout</a>
    </div>
</nav>

 <!-- Print Button -->
    <button class="print-btn" onclick="window.print()">Print</button>

<div class="container">
    <h2>Notifications</h2>
    <ul class="notification-list">
        <?php foreach ($notifications as $notification): ?>
        <li class="notification-item">
            <p><?= htmlspecialchars($notification['message']) ?></p>
            <small><?= htmlspecialchars($notification['created_at']) ?></small>

            <div class="notification-actions">
                <a href="view_notification.php?id=<?= $notification['id'] ?>">View</a>
                <a href="?delete_id=<?= $notification['id'] ?>" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<!--<div class="footer">
    &copy; 2024 Barangay Management System. All Rights Reserved.
</div>-->

</body>
</html>
