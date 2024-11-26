<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $payment_mode = $_POST['payment_mode'];
    $user_id = $_SESSION['user_id'];

    // Fetch reservation details
    $sql = "SELECT * FROM reservations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        echo "Reservation not found.";
        exit();
    }

    // Insert payment into payments table
    $sql = "INSERT INTO payments (reservation_id, user_id, payment_mode, amount, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $amount = calculateAmount($reservation['item_id'], $reservation['quantity']); // Define this function as needed
    $stmt->execute([$reservation_id, $user_id, $payment_mode, $amount]);

    // Notify admin
    $message = "Payment received for reservation ID: $reservation_id. Amount: ₱" . number_format($amount, 2) . ".";
    $sql = "INSERT INTO notifications (user_id, message, status, created_at) VALUES (?, ?, 'unread', NOW())";
    $stmt = $conn->prepare($sql);
    $admin_id = 1; // Replace with your admin ID logic
    $stmt->execute([$admin_id, $message]);

    echo "Payment successfully processed and admin notified.";
    header('Location: ../reservations/payment_success.php');
    exit();
}

function calculateAmount($item_id, $quantity) {
    global $conn;

    // Fetch item price
    $sql = "SELECT price FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        return $item['price'] * $quantity;
    }

    return 0; // Default amount if item not found
}
