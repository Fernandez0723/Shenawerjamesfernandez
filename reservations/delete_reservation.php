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

// Prepare and execute the deletion query
$sql = "DELETE FROM reservations WHERE id = :reservation_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Redirect back to the reservations page with a success message
    header('Location: view_reservations.php?message=Reservation deleted successfully.');
    exit();
} else {
    // Redirect back with an error message
    header('Location: view_reservations.php?error=Failed to delete reservation.');
    exit();
}
?>
