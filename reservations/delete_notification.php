<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle the deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Simulate a delay to show the loading animation (2 seconds)
    sleep(2); // You can adjust the sleep time for testing purposes.

    // Prepare delete query
    $sql = "DELETE FROM admin_notifications WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to notifications list after deletion
    header('Location: admin_notification.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
            display: none; /* Hide the body initially */
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

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- Loading animation -->
<div class="loading-container" id="loading">
    <div>
        <div class="loading-spinner"></div>
        <div class="loading-message">Weak Signal, Please wait...</div>
    </div>
</div>

<script>
    // Simulate delay (2 seconds)
    setTimeout(function() {
        document.getElementById('loading').style.display = 'none';  // Hide the loading animation
        window.location.href = 'admin_notification.php';  // Redirect to the notification list
    }, 2000);  // 2 seconds delay
</script>

</body>
</html>
