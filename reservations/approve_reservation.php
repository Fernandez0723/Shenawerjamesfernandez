<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    $action = $_GET['action']; // 'approve' or 'decline'

    // Fetch reservation details
    $sql = "SELECT user_id, item_id FROM reservations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        $status = ($action === 'approve') ? 'approved' : 'declined';
        $message = ($action === 'approve') 
            ? "Your reservation has been approved!" 
            : "Your reservation has been declined.";

        // Update reservation status
        $sql = "UPDATE reservations SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$status, $reservation_id]);

        // Add notification for the user
        $sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$reservation['user_id'], $message]);

        echo "Reservation $status successfully!";
    } else {
        echo "Reservation not found!";
    }
}
?>
