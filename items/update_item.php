<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid item ID.";
    exit();
}

$id = $_GET['id'];
$item = null;

// Fetch item details
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Handle image update
    $image = $item['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image);
    }

    // Update item details
    $sql = "UPDATE items SET name = ?, description = ?, quantity = ?, price = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $description, $quantity, $price, $image, $id]);

    echo "Item updated successfully!";
    header('Location: add_item.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

        h1, h2 {
    color: #333;
    margin-top: 20px;
}

nav {
    background-color: #007bff;
    padding: 10px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav .logo {
    flex: 1;
}

nav .nav-links {
    display: flex;
    justify-content: flex-end;
    flex: 3;
}

nav a {
    text-decoration: none;
    color: white;
    margin-right: 20px;
    font-weight: bold;
}

nav a:hover {
    text-decoration: underline;
}

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="number"], textarea {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .actions {
            display: flex;
            justify-content: space-between;
        }

        .cancel-btn {
            background-color: #ccc;
            color: #333;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .cancel-btn:hover {
            background-color: #999;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

    </style>
</head>
<body>
    <nav>
    <div class="logo">
        <img src="path_to_your_logo.png" alt="Logo" style="height: 40px;"> <!-- Adjust logo size -->
    </div>
    <div class="nav-links">
        <!--<a href="../dashboard/admin_dashboard.php">Dashboard</a>
        <a href="../items/add_item.php">Add Items</a>
        <a href="../reservations/view_reservations.php">View Reservations</a>
        <a href="../reservations/payment_record.php">Payment Record</a>
        <a href="../reservations/admin_notification.php">Notification</a>
        <a href="../logout.php">Logout</a>-->
    </div>
</nav>

<div class="container">
    <h2>Update Item</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label>Item Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required><br>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($item['price']) ?>" required><br>

        <label>Image:</label>
        <input type="file" name="image" accept="image/*"><br>

        <div class="actions">
            <button type="submit">Update Item</button>
            <a href="add_item.php"><button type="button" class="cancel-btn">Cancel</button></a>
        </div>
    </form>
</div>

<!--<div class="footer">
    <p>&copy; 2024 Barangay Community Rental System</p>
</div>-->

</body>
</html>
