<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch the notification by ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM admin_notifications WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $notification = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$notification) {
        echo "Notification not found.";
        exit();
    }
} else {
    echo "No notification ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
            display: none; /* Hide the body initially */
        }

        .notification-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none; /* Hide initially */
        }

        .notification-details h2 {
            color: #333;
        }

        .notification-details p {
            font-size: 16px;
            color: #555;
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

        .loading-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-message {
            text-align: center;
            color: #007bff;
            font-size: 18px;
            margin-top: 20px;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        .rotating-image {
            width: 50px;
            height: 50px;
            animation: rotateImage 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes rotateImage {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .image-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Loading animation -->
<div class="loading-container" id="loading">
    <center><div class="image-container">
        <img src="uploads/7c3f7fff6f45e89461c6afe821a9b928.jpg" class="rotating-image" alt="Rotating Image">
    </div></center>
    <div class="loading-message">Poor Connection, Please wait...</div>
</div>

<div class="notification-details" id="notificationDetails">
    <h2>Notification Details</h2>
    <p><strong>Message:</strong> <?= htmlspecialchars($notification['message']) ?></p>
    <p><strong>Created At:</strong> <?= htmlspecialchars($notification['created_at']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($notification['status']) ?></p>
    <a href="admin_notification.php">Back to Notifications</a>
</div>

<!--<div class="footer">
    &copy; 2024 Barangay Management System. All Rights Reserved.
</div>-->
<script>
    // Initially, set the loading animation to visible
    document.getElementById('loading').style.display = 'block';  // Show the loading animation

    // Simulate delay (2 seconds)
    setTimeout(function() {
        document.getElementById('loading').style.display = 'none';  // Hide the loading animation
        document.getElementById('notificationDetails').style.display = 'block';  // Show the notification content
        document.body.style.display = 'block';  // Show the body content
    }, 1);  // 2 seconds delay
</script>


</body>
</html>
