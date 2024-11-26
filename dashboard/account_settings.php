<?php
require '../database/config.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT fullname, username, email, contact_number, address, profile_picture, role, created_at, status FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="../styles/styles.css">
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

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .profile-info img {
            display: block;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
        }

        .profile-info {
            margin-top: 20px;
        }

        .profile-info p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .profile-info strong {
            color: #333;
        }

        .edit-btn {
            display: block;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-btn:hover {
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
            <a href="../dashboard/notifications.php">Notifications</a>
            <a href="account_settings.php">Account Settings</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="container">
        <h1>Account Settings</h1>
        <div class="profile-info">
            <img src="../<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture">
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($user['fullname']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Contact Number:</strong> <?= htmlspecialchars($user['contact_number']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
            <p><strong>Account Created At:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($user['status']) ?></p>
        </div>
        <a href="edit_account.php" class="edit-btn">Edit Profile</a>
    </div>
</body>
</html>
