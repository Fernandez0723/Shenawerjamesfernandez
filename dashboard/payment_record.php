<?php
require '../database/config.php';
session_start();

// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch payment records
try {
    $sql = "SELECT payments.*, users.full_name, reservations.reserved_date, reservations.return_date 
            FROM payments
            JOIN users ON payments.user_id = users.id
            JOIN reservations ON payments.reservation_id = reservations.id
            ORDER BY payments.created_at DESC";
    $stmt = $conn->query($sql);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
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
    </style>
</head>
<body>
    <h2>Payment Records</h2>

    <?php if (!empty($payments)): ?>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Reservation ID</th>
                    <th>User</th>
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
                        <td><?= htmlspecialchars($payment['full_name']) ?></td>
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
</body>
</html>
