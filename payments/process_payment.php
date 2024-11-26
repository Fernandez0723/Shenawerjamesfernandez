<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch POST data
    $reservation_id = $_POST['reservation_id'];
    $payment_mode = $_POST['payment_mode'];
    $total_amount = $_POST['total_amount'];
    $delivery = $_POST['delivery'];
    $user_id = $_SESSION['user_id'];

    // Validate inputs
    if (empty($reservation_id) || empty($payment_mode) || empty($delivery)) {
        echo "Missing payment information.";
        exit();
    }

    try {
        // Insert payment into payments table
        $sql = "INSERT INTO payments (reservation_id, user_id, payment_mode, amount, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$reservation_id, $user_id, $payment_mode, $total_amount]);

        // Update the reservation status to 'completed'
        $sql = "UPDATE reservations SET status = 'completed', payment_mode = ?, total_amount = ?, delivery_option = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$payment_mode, $total_amount, $delivery, $reservation_id, $user_id]);

        // Notify admin
        $message = "Payment received for Reservation ID: $reservation_id. Amount: ₱" . number_format($total_amount, 2) . ". Delivery Option: $delivery.";
        $sql = "INSERT INTO admin_notifications (user_id, message, status, created_at) VALUES (?, ?, 'unread', NOW())";
        $stmt = $conn->prepare($sql);
        $admin_id = 1; // Replace with your admin logic
        $stmt->execute([$admin_id, $message]);

        // Redirect to success page
        header('Location: payment_success.php?reservation_id=' . $reservation_id);
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
