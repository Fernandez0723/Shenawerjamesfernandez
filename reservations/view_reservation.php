<?php
session_start();
require '../database/config.php'; // Include your database connection

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Check if reservation ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: view_reservations.php');
    exit();
}

$reservation_id = $_GET['id'];

// Fetch reservation details from the database
$sql = "SELECT r.id, u.fullname, u.email, u.address, i.name AS item_name, r.reserved_date, r.quantity, r.status 
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN items i ON r.item_id = i.id
        WHERE r.id = :reservation_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    echo "Reservation not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-back {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <h1>Reservation Details</h1>

    <table>
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($reservation['id']) ?></td>
        </tr>
        <tr>
            <th>Resident</th>
            <td><?= htmlspecialchars($reservation['fullname']) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($reservation['email']) ?></td>
        </tr>
        <tr>
            <th>Address</th>
            <td><?= htmlspecialchars($reservation['address']) ?></td>
        </tr>
        <tr>
            <th>Item</th>
            <td><?= htmlspecialchars($reservation['item_name']) ?></td>
        </tr>
        <tr>
            <th>Reserved Date</th>
            <td><?= htmlspecialchars($reservation['reserved_date']) ?></td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td><?= htmlspecialchars($reservation['quantity']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($reservation['status']) ?></td>
        </tr>
    </table>

    <br>
    <a href="view_reservations.php" class="btn btn-back">Back to Reservations</a>
</body>
</html>
