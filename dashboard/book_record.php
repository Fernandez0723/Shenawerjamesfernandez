<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

// Fetch available items for the booking form
$sql = "SELECT * FROM items WHERE availability = 'available'";
$stmt = $conn->query($sql);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $user_id = $_SESSION['user_id'];
    $reserved_date = $_POST['reserved_date'];
    $return_date = $_POST['return_date'];
    $quantity = $_POST['quantity'];

    // Fetch item details to check if there is enough quantity
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();

    if ($item && $item['quantity'] >= $quantity) {
        // Insert reservation with 'pending' status
        $sql = "INSERT INTO reservations (user_id, item_id, reserved_date, return_date, quantity, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $item_id, $reserved_date, $return_date, $quantity]);

        // Reduce item quantity based on the reserved quantity
        $sql = "UPDATE items SET quantity = quantity - ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$quantity, $item_id]);

        echo "<script>alert('Booking request submitted!');</script>";
    } else {
        echo "<script>alert('Sorry, not enough stock available.');</script>";
    }
}

// Fetch the reservations for the logged-in resident, grouped by status
$sql = "SELECT reservations.*, items.name, items.image FROM reservations 
        JOIN items ON reservations.item_id = items.id 
        WHERE reservations.user_id = ? 
        ORDER BY reservations.status DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Item</title>
    <link rel="stylesheet" href="../styles/styles.css"> <!-- Link your stylesheet -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        form select,
        form input[type="number"],
        form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            background-color: #007bff;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
        }

        table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
<div class="navbar">
    <div class="logo">Barangay Rental System</div>
    <div>
        <a href="../dashboard/resident_dashboard.php">Dashboard</a>
        <a href="../reservations/book_item.php">Book Items</a>
        <a href="../dashboard/book_record.php">Book Record</a>
        <a href="notifications.php">Notifications</a>
        <a href="../dashboard/account_settings.php">Account Setting</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<center><h3>Your Booked Items</h3>
<?php
$statuses = ['pending', 'approved', 'completed', 'declined'];
foreach ($statuses as $status):
?>
    <h4><?= ucfirst($status) ?> Reservations</h4>
    <table border="1">
        <thead>
            <tr>
                <th>Item Image</th>
                <th>Item Name</th>
                <th>Reserved Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Quantity</th>
                <?php if ($status != 'pending'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <?php if ($reservation['status'] == $status): ?>
                <tr>
                    <td><img src="../<?= htmlspecialchars($reservation['image']) ?>" alt="Item Image"></td>
                    <td><?= htmlspecialchars($reservation['name']) ?></td>
                    <td><?= htmlspecialchars($reservation['reserved_date']) ?></td>
                    <td><?= htmlspecialchars($reservation['return_date']) ?></td>
                    <td><?= ucfirst($reservation['status']) ?></td>
                    <td><?= htmlspecialchars($reservation['quantity']) ?></td>
                    <?php if ($status != 'pending'): ?>
                        <td>
                            <?php if ($status == 'approved' || $status == 'declined'): ?>
                                <a href="../reservations/view_reservation2.php?id=<?= $reservation['id'] ?>">View</a>
                            <?php elseif ($status == 'completed'): ?>
                                <a href="../reservations/view_reservation1.php?id=<?= $reservation['id'] ?>">View</a>
                            <?php endif; ?>
                            | 
                            <a href="../reservations/delete_reservation1.php?id=<?= $reservation['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                            <?php if ($status == 'approved'): ?>
                                | <a href="../payments/send_payment.php?reservation_id=<?= $reservation['id'] ?>">Send Payment</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

   <!-- <footer>
        <p>© 2024 Barangay Rental System. All Rights Reserved.</p>
    </footer> -->
</body>
</html>