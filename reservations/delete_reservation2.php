<?php
session_start();
require '../database/config.php'; // Include your database connection

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Check if reservation ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request. Reservation ID is missing.";
    exit();
}

$reservation_id = $_GET['id'];

try {
    // Check if the reservation exists
    $sql_check = "SELECT id FROM reservations WHERE id = :id";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':id', $reservation_id, PDO::PARAM_INT);
    $stmt_check->execute();

    if ($stmt_check->rowCount() == 0) {
        echo "Reservation not found.";
        exit();
    }

    // Delete the reservation
    $sql_delete = "DELETE FROM reservations WHERE id = :id";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $reservation_id, PDO::PARAM_INT);
    $stmt_delete->execute();

    // Redirect to the dashboard or confirmation page
    header('Location: ../admin/dashboard.php?message=Reservation+deleted+successfully');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
