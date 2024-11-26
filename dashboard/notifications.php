<?php
require '../database/config.php'; // Include your database connection
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

// Fetch notifications for the logged-in resident
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Main Content */
        .container {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            text-align: center;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td a {
            color: #007bff;
            text-decoration: none;
        }
        td a:hover {
            text-decoration: underline;
        }

        .back-button {
            display: block;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            width: 200px;
            margin: 20px auto;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

   <!-- Navbar -->
<div class="navbar">
    <div class="logo">Barangay Rental System</div>
    <div>
        <a href="../dashboard/resident_dashboard.php">Dashboard</a>
        <a href="../reservations/book_item.php">Book Items</a>
        <a href="../dashboard/book_record.php">Book Record</a>
        <a href="notifications.php">Notifications</a>
        <a href="../dashboard/account_settings.php">Account Setting</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

    <!-- Main Content -->
    <div class="container">
        <h2>Your Notifications</h2>
        <table>
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                <tr>
                    <td><?= htmlspecialchars($notification['message']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($notification['status'])) ?></td>
                    <td><?= htmlspecialchars($notification['created_at']) ?></td>
                    <td>
                        <?php if ($notification['status'] == 'unread'): ?>
                        <a href="mark_read.php?id=<?= $notification['id'] ?>">Mark as Read</a>
                        <?php endif; ?>
                        <a href="delete_notification.php?id=<?= $notification['id'] ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../dashboard/resident_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

</body>
</html>
