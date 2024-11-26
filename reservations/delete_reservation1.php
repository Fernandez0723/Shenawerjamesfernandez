<?php
require '../database/config.php';
session_start();

// Ensure that the user is a resident
if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

// Check if the reservation_id is passed via URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Reservation ID is required.";
    exit();
}

$reservation_id = $_GET['id'];

// Validate that the reservation_id is numeric
if (!is_numeric($reservation_id)) {
    echo "Invalid Reservation ID.";
    exit();
}

// Fetch reservation details
$sql = "SELECT * FROM reservations WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$reservation_id, $_SESSION['user_id']]);
$reservation = $stmt->fetch();

if (!$reservation) {
    echo "Reservation not found or you do not have permission to delete it.";
    exit();
}

// Delete related payments first, if any
$sql = "DELETE FROM payments WHERE reservation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$reservation_id]);

// Now delete the reservation itself
$sql = "DELETE FROM reservations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$reservation_id]);

// Optionally, you can update the quantity of the item back if needed, depending on the system flow
// $sql = "UPDATE items SET quantity = quantity + ? WHERE id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->execute([$reservation['quantity'], $reservation['item_id']]);

// Success message or redirect
echo "Reservation has been deleted successfully.";
header('Location: book_item.php'); // Redirect to the booking page or reservations page
exit();
?>
