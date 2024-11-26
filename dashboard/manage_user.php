<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch all users
$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Block/Unblock User functionality
if (isset($_GET['action']) && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'block') {
        // Block the user by setting the status to 'blocked'
        $sql = "UPDATE users SET status = 'blocked' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
    } elseif ($action == 'unblock') {
        // Unblock the user by setting the status to 'active'
        $sql = "UPDATE users SET status = 'active' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
    }

    // Redirect to the user management page after the action
    header('Location: manage_user.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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



        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .action-buttons {
            display: flex;
            justify-content: space-around;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .block-btn {
            background-color: #ffc107;
        }

        .action-buttons a:hover {
            opacity: 0.8;
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
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

<div class="container">
    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Profile Picture</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td>
                <?php if ($user['profile_picture']): ?>
                    <img src="../<?= htmlspecialchars($user['profile_picture']) ?>" class="profile-picture" alt="Profile Picture">
                <?php else: ?>
                    <img src="../images/default-profile.png" class="profile-picture" alt="Default Profile Picture">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['fullname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['address']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><?= htmlspecialchars($user['created_at']) ?></td>
            <td><?= htmlspecialchars($user['status']) ?></td>
            <td class="action-buttons">
                <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a>
                <a href="delete_user.php?id=<?= $user['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>

                <?php if ($user['status'] == 'active'): ?>
                    <a href="?action=block&id=<?= $user['id'] ?>" class="block-btn" onclick="return confirm('Are you sure you want to block this user?')">Block</a>
                <?php else: ?>
                    <a href="?action=unblock&id=<?= $user['id'] ?>" class="block-btn" onclick="return confirm('Are you sure you want to unblock this user?')">Unblock</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
