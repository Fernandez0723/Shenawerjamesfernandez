<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['reservation_id'])) {
    echo "Reservation ID is required.";
    exit();
}

$reservation_id = $_GET['reservation_id'];

// Fetch payment details
$sql = "SELECT payments.*, reservations.*, items.name AS item_name 
        FROM payments 
        JOIN reservations ON payments.reservation_id = reservations.id
        JOIN items ON reservations.item_id = items.id
        WHERE payments.reservation_id = ? AND payments.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$reservation_id, $_SESSION['user_id']]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    echo "Payment not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Thank you for your payment!</p>
    <p><strong>Reservation ID:</strong> <?= htmlspecialchars($reservation_id) ?></p>
    <p><strong>Item Name:</strong> <?= htmlspecialchars($payment['item_name']) ?></p>
    <p><strong>Quantity:</strong> <?= htmlspecialchars($payment['quantity']) ?></p>
    <p><strong>Total Amount Paid:</strong> PHP <?= number_format($payment['amount'], 2) ?></p>
    <p><strong>Delivery Option:</strong> <?= htmlspecialchars($payment['delivery_option']) ?></p>
    <p><strong>Payment Mode:</strong> <?= htmlspecialchars($payment['payment_mode']) ?></p>
    <a href="../dashboard/resident_dashboard.php">Return to Dashboard</a>
</body>
</html>
