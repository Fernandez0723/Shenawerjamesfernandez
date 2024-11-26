<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "Reservation ID is required.";
    exit();
}

$reservation_id = $_GET['id'];
$sql = "SELECT reservations.*, items.name AS item_name, items.image AS item_image, 
                reservations.total_amount, reservations.status 
        FROM reservations 
        JOIN items ON reservations.item_id = items.id 
        WHERE reservations.id = ? AND reservations.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$reservation_id, $_SESSION['user_id']]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    echo "Reservation not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservation</title>
    <style>
/* Existing CSS remains unchanged */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}
.receipt-container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.receipt-header {
    text-align: center;
    margin-bottom: 20px;
}
.receipt-header h2 {
    margin: 0;
    color: #007bff;
}
.company-details {
    font-size: 14px;
    color: #777;
    text-align: center;
    margin-bottom: 20px;
}
.company-details strong {
    color: #333;
}
.item-image {
    width: 50%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 20px;
}
.receipt-details {
    margin-bottom: 20px;
}
.receipt-details p {
    font-size: 16px;
    margin: 5px 0;
    color: #333;
}
.receipt-details p strong {
    color: #007bff;
}
.back-button {
    display: block;
    text-align: center;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    width: 200px;
    margin: 20px auto;
    font-weight: bold;
}
.back-button:hover {
    background-color: #0056b3;
}
.print-button {
    display: block;
    text-align: center;
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    width: 200px;
    margin: 10px auto;
    font-weight: bold;
}
.print-button:hover {
    background-color: #218838;
}

/* CSS for Print */
@media print {
    .back-button, .print-button {
        display: none;
    }
    .receipt-container {
        box-shadow: none;
        border: 1px solid #000;
    }
}
    </style>
</head>
<body>

    <div class="receipt-container">
        <!-- Company Details -->
        <div class="company-details">
            <h2>Barangay Rental System</h2>
            <p><strong>Barangay Poblacion</strong><br>
               Barangay Poblacion, Tupi<br>
               Contact: (123) 456-7890<br>
               Website: www.barangaypoblaciontupirental.com</p>
        </div>
        
        <div class="receipt-header">
            <h3>Reservation Receipt</h3>
            <p><strong>Reservation Details</strong></p>
        </div>
        
        <!-- Item Image -->
        <center><img src="../<?= htmlspecialchars($reservation['item_image']) ?>" alt="Item Image" class="item-image"></center>
        
        <div class="receipt-details">
            <p><strong>Item Name:</strong> <?= htmlspecialchars($reservation['item_name']) ?></p>
            <p><strong>Reserved Date:</strong> <?= htmlspecialchars($reservation['reserved_date']) ?></p>
            <p><strong>Return Date:</strong> <?= htmlspecialchars($reservation['return_date']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($reservation['status'])) ?></p>
            <p><strong>Quantity:</strong> <?= htmlspecialchars($reservation['quantity']) ?></p>
        </div>
        
        <!-- Back Button -->
        <a href="javascript:history.back()" class="back-button">Back to Reservations</a>

        <!-- Print Button -->
        <a href="javascript:window.print()" class="print-button">Print Receipt</a>
    </div>

</body>
</html>
