<?php
session_start();
require '../database/config.php'; // Include your database connection

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch pending reservations
$sql_pending = "SELECT r.id, u.fullname, i.name AS item_name, r.reserved_date, r.return_date, r.created_at, r.quantity 
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN items i ON r.item_id = i.id
                WHERE r.status = 'pending' 
                ORDER BY r.reserved_date ASC";
$stmt_pending = $conn->prepare($sql_pending);
$stmt_pending->execute();
$pending_reservations = $stmt_pending->fetchAll(PDO::FETCH_ASSOC);

// Fetch approved reservations
$sql_approved = "SELECT r.id, u.fullname, i.name AS item_name, r.reserved_date, r.return_date, r.created_at, r.quantity 
                 FROM reservations r
                 JOIN users u ON r.user_id = u.id
                 JOIN items i ON r.item_id = i.id
                 WHERE r.status = 'approved' 
                 ORDER BY r.reserved_date ASC";
$stmt_approved = $conn->prepare($sql_approved);
$stmt_approved->execute();
$approved_reservations = $stmt_approved->fetchAll(PDO::FETCH_ASSOC);

// Fetch declined reservations
$sql_declined = "SELECT r.id, u.fullname, i.name AS item_name, r.reserved_date, r.return_date, r.created_at, r.quantity 
                 FROM reservations r
                 JOIN users u ON r.user_id = u.id
                 JOIN items i ON r.item_id = i.id
                 WHERE r.status = 'declined' 
                 ORDER BY r.reserved_date ASC";
$stmt_declined = $conn->prepare($sql_declined);
$stmt_declined->execute();
$declined_reservations = $stmt_declined->fetchAll(PDO::FETCH_ASSOC);

// Fetch payment completed reservations
$sql_payment_completed = "SELECT r.id, u.fullname, i.name AS item_name, r.reserved_date, r.return_date, r.created_at, r.quantity, r.total_amount, r.payment_mode 
                          FROM reservations r
                          JOIN users u ON r.user_id = u.id
                          JOIN items i ON r.item_id = i.id
                          WHERE r.status = 'completed'
                          ORDER BY r.reserved_date ASC";
$stmt_payment_completed = $conn->prepare($sql_payment_completed);
$stmt_payment_completed->execute();
$payment_completed_reservations = $stmt_payment_completed->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }
        h1, h2 {
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        img {
            width: 100px;
            height: auto;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007BFF;
        }
        .actions a:hover {
            text-decoration: underline;
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
        <div class="logo">Barangay Rental System</div> <!-- Adjust logo size as needed -->
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


    <h2>Pending Reservations</h2>
    <?php if (count($pending_reservations) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Resident</th>
                <th>Item</th>
                <th>Reserved Date</th>
                <th>Return Date</th>
                <th>Created At</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pending_reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['id']) ?></td>
                <td><?= htmlspecialchars($reservation['fullname']) ?></td>
                <td><?= htmlspecialchars($reservation['item_name']) ?></td>
                <td><?= htmlspecialchars($reservation['reserved_date']) ?></td>
                <td><?= isset($reservation['return_date']) ? htmlspecialchars($reservation['return_date']) : 'N/A' ?></td>
                <td><?= isset($reservation['created_at']) ? htmlspecialchars($reservation['created_at']) : 'N/A' ?></td>
                <td><?= htmlspecialchars($reservation['quantity']) ?></td>
                <td class="actions">
                    <a href="../reservations/approve_reservation.php?id=<?= $reservation['id'] ?>&action=approve">Approve</a>
                    <a href="../reservations/approve_reservation.php?id=<?= $reservation['id'] ?>&action=decline">Decline</a>
                    <a href="../reservations/view_reservation.php?id=<?= $reservation['id'] ?>">View</a>
                    <a href="../reservations/delete_reservation.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No pending reservations at the moment.</p>
    <?php endif; ?>

    <h2>Approved Reservations</h2>
    <?php if (count($approved_reservations) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Resident</th>
                <th>Item</th>
                <th>Reserved Date</th>
                <th>Return Date</th>
                <th>Created At</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($approved_reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['id']) ?></td>
                <td><?= htmlspecialchars($reservation['fullname']) ?></td>
                <td><?= htmlspecialchars($reservation['item_name']) ?></td>
                <td><?= htmlspecialchars($reservation['reserved_date']) ?></td>
                <td><?= isset($reservation['return_date']) ? htmlspecialchars($reservation['return_date']) : 'N/A' ?></td>
                <td><?= isset($reservation['created_at']) ? htmlspecialchars($reservation['created_at']) : 'N/A' ?></td>
                <td><?= htmlspecialchars($reservation['quantity']) ?></td>
                <td class="actions">
                    <a href="../reservations/view_reservation.php?id=<?= $reservation['id'] ?>">View</a>
                    <a href="../reservations/delete_reservation.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No approved reservations.</p>
    <?php endif; ?>

<h2>Payment Completed Reservations</h2>
    <?php if (count($payment_completed_reservations) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Resident</th>
                <th>Item</th>
                <th>Reserved Date</th>
                <th>Return Date</th>
                <th>Created At</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Payment Mode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payment_completed_reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['id']) ?></td>
                <td><?= htmlspecialchars($reservation['fullname']) ?></td>
                <td><?= htmlspecialchars($reservation['item_name']) ?></td>
                <td><?= htmlspecialchars($reservation['reserved_date']) ?></td>
                <td><?= isset($reservation['return_date']) ? htmlspecialchars($reservation['return_date']) : 'N/A' ?></td>
                <td><?= isset($reservation['created_at']) ? htmlspecialchars($reservation['created_at']) : 'N/A' ?></td>
                <td><?= htmlspecialchars($reservation['quantity']) ?></td>
                <td><?= htmlspecialchars($reservation['total_amount']) ?></td>
                <td><?= htmlspecialchars($reservation['payment_mode']) ?></td>
                <td class="actions">
                    <a href="../reservations/view_reservation.php?id=<?= $reservation['id'] ?>">View</a>
                    <a href="../reservations/delete_reservation.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No completed payment reservations.</p>
    <?php endif; ?>

    <h2>Declined Reservations</h2>
    <?php if (count($declined_reservations) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Resident</th>
                <th>Item</th>
                <th>Reserved Date</th>
                <th>Return Date</th>
                <th>Created At</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($declined_reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['id']) ?></td>
                <td><?= htmlspecialchars($reservation['fullname']) ?></td>
                <td><?= htmlspecialchars($reservation['item_name']) ?></td>
                <td><?= htmlspecialchars($reservation['reserved_date']) ?></td>
                <td><?= isset($reservation['return_date']) ? htmlspecialchars($reservation['return_date']) : 'N/A' ?></td>
                <td><?= isset($reservation['created_at']) ? htmlspecialchars($reservation['created_at']) : 'N/A' ?></td>
                <td><?= htmlspecialchars($reservation['quantity']) ?></td>
                <td class="actions">
                    <a href="../reservations/view_reservation.php?id=<?= $reservation['id'] ?>">View</a>
                    <a href="../reservations/delete_reservation.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No declined reservations.</p>
    <?php endif; ?>
</body>
</html>