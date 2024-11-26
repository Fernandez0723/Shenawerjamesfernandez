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

// Fetch reservation details
$sql = "SELECT reservations.*, items.name AS item_name, items.price 
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
<html>
<head>
    <title>Send Payment</title>
    <script>
        function calculateTotal() {
            const deliveryOption = document.querySelector('input[name="delivery"]:checked');
            const baseAmount = <?= htmlspecialchars($reservation['quantity'] * $reservation['price']) ?>; // Base price from DB
            let totalAmount = baseAmount;

            if (deliveryOption) {
                if (deliveryOption.value === "outside") {
                    totalAmount += 500;
                } else if (deliveryOption.value === "inside") {
                    totalAmount += 200;
                }
            }

            document.getElementById("total_amount").innerText = "Total Amount: PHP " + totalAmount;
            document.getElementById("total_amount_input").value = totalAmount;
        }
    </script>
</head>
<body>
    <h2>Payment for Reservation</h2>
    <p><strong>Item Name:</strong> <?= htmlspecialchars($reservation['item_name']) ?></p>
    <p><strong>Reserved Date:</strong> <?= htmlspecialchars($reservation['reserved_date']) ?></p>
    <p><strong>Return Date:</strong> <?= htmlspecialchars($reservation['return_date']) ?></p>
    <p><strong>Quantity:</strong> <?= htmlspecialchars($reservation['quantity']) ?></p>
    <p><strong>Base Amount:</strong> PHP <?= htmlspecialchars($reservation['quantity'] * $reservation['price']) ?></p>

    <form action="process_payment.php" method="POST">
        <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">
        <input type="hidden" id="total_amount_input" name="total_amount" value="<?= $reservation['quantity'] * $reservation['price'] ?>">
        
        <label for="delivery">Select Delivery Option:</label><br>
        <input type="radio" name="delivery" value="pickup" onclick="calculateTotal()" required> Pickup (No extra charge)<br>
        <input type="radio" name="delivery" value="inside" onclick="calculateTotal()"> Deliver (Inside Barangay: PHP 200)<br>
        <input type="radio" name="delivery" value="outside" onclick="calculateTotal()"> Deliver (Outside Barangay: PHP 500)<br><br>

        <label for="payment_mode">Select Payment Method:</label>
        <select name="payment_mode" required>
            <option value="GCash">GCash</option>
            <option value="PayMaya">PayMaya</option>
            <option value="Cash">Cash on Delivery</option>
        </select><br><br>

        <p id="total_amount">Total Amount: PHP <?= htmlspecialchars($reservation['quantity'] * $reservation['price']) ?></p>

        <button type="submit">Proceed to Payment</button>
    </form>
    <a href="javascript:history.back()">Back</a>
</body>
</html>
