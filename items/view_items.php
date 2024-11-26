<?php
require '../database/config.php';
session_start();

// Fetch items including quantity, price, and image
$sql = "SELECT * FROM items";
$stmt = $conn->query($sql);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Items</title>
    <style>
        img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>Available Items</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Image</th>
                <th>Availability</th>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                <th>Actions</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td>
                    <?php if ($item['image']): ?>
                        <img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <?php else: ?>
                        No image available
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    // Check if the quantity is 0, and set availability to 'unavailable'
                    if ($item['quantity'] == 0) {
                        echo 'Unavailable';
                    } else {
                        echo ucfirst($item['availability']);
                    }
                    ?>
                </td>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                <td>
                    <a href="edit_item.php?id=<?= $item['id'] ?>">Edit</a> | 
                    <a href="delete_item.php?id=<?= $item['id'] ?>">Delete</a>
                </td>
                <?php } ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="../dashboard/<?= $_SESSION['role'] ?>_dashboard.php">Back to Dashboard</a>
</body>
</html>
