<?php
require '../database/config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

try {
    // Updated query to include item name, address, quantity, and to calculate total amount
    $sql = "SELECT payments.*, 
                   users.fullname, 
                   users.address, 
                   reservations.reserved_date, 
                   reservations.return_date, 
                   reservations.quantity, 
                   items.name
            FROM payments
            JOIN users ON payments.user_id = users.id
            JOIN reservations ON payments.reservation_id = reservations.id
            JOIN items ON reservations.item_id = items.id
            ORDER BY payments.created_at DESC";
    $stmt = $conn->query($sql);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to calculate the total amount of all payments
    $totalSql = "SELECT SUM(amount) AS total_amount FROM payments";
    $totalStmt = $conn->query($totalSql);
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC);
    $totalAmount = $total['total_amount'] ?? 0;  // Default to 0 if no payments exist
} catch (PDOException $e) {
    die("Error fetching payment records: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payments</title>
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
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .total {
            font-size: 1.2em;
            margin-top: 20px;
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
    <h2>Payment Records</h2>

    <?php if (!empty($payments)): ?>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Reservation ID</th>
                    <th>User</th>
                    <th>Address</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Payment Mode</th>
                    <th>Amount</th>
                    <th>Reserved Date</th>
                    <th>Return Date</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= htmlspecialchars($payment['id']) ?></td>
                        <td><?= htmlspecialchars($payment['reservation_id']) ?></td>
                        <td><?= htmlspecialchars($payment['fullname']) ?></td>
                        <td><?= htmlspecialchars($payment['address']) ?></td>
                        <td><?= htmlspecialchars($payment['name']) ?></td>
                        <td><?= htmlspecialchars($payment['quantity']) ?></td>
                        <td><?= htmlspecialchars($payment['payment_mode']) ?></td>
                        <td>₱<?= number_format($payment['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($payment['reserved_date']) ?></td>
                        <td><?= htmlspecialchars($payment['return_date']) ?></td>
                        <td><?= htmlspecialchars($payment['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No payment records found.</p>
    <?php endif; ?>

    <div class="total">
        <strong>Total Payments: </strong>₱<?= number_format($totalAmount, 2) ?>
    </div>
</body>
</html>
