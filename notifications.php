<?php
require 'config.php';  // Adjusted to the correct path
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
<html>
<head>
    <title>Notifications</title>
</head>
<body>
    <h2>Your Notifications</h2>
    <table border="1">
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
                <td><?= $notification['message'] ?></td>
                <td><?= ucfirst($notification['status']) ?></td>
                <td><?= $notification['created_at'] ?></td>
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
    <br>
    <a href="../Baranggay%20Community%20Rental%20System/dashboard/resident_dashboard.php">Back to Dashboard</a>
</body>
</html>
