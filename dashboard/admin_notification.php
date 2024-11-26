<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch notifications
$sql = "SELECT * FROM admin_notifications WHERE user_id = 1 ORDER BY created_at DESC"; // Replace `1` with admin ID
$stmt = $conn->query($sql);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Notifications</title>
</head>
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
    justify-content: space-between; /* Space between logo and navigation links */
    align-items: center; /* Vertically align the content */
}

nav .logo {
    flex: 1; /* This will push the logo to the left */
}

nav .nav-links {
    display: flex; /* Flex for the links */
    justify-content: flex-end; /* Center the links */
    flex: 3; /* Give the links a bit more space to center properly */
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
        .card {
            background-color: white;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: inline-block;
            width: 23%;
            text-align: center;
            cursor: pointer;
        }
        .card h3 {
            margin: 0;
            font-size: 24px;
        }
        .card p {
            font-size: 18px;
            color: #666;
        }
        .card .total {
            font-size: 32px;
            color: #007bff;
            font-weight: bold;
        }
        .card:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        .chart-container {
            margin-top: 40px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            margin: 20px auto;
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
</style>
<body>
    <nav>
    <div class="logo">
        <img src="path_to_your_logo.png" alt="Logo" style="height: 40px;"> <!-- Adjust logo size as needed -->
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
    <h2>Notifications</h2>
    <ul>
        <?php foreach ($notifications as $notification): ?>
        <li>
            <p><?= htmlspecialchars($notification['message']) ?></p>
            <small><?= htmlspecialchars($notification['created_at']) ?></small>
        </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
