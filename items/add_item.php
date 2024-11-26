<?php
require '../database/config.php';
session_start();

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle item form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'] ?? 0.00;  // Default to 0.00 if price is not provided

    // Check if price is not empty
    if (!empty($price) && is_numeric($price)) {
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image);
        }

        // Insert item into the database
        $sql = "INSERT INTO items (name, description, quantity, price, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $quantity, $price, $image]);

        echo "Item added successfully!";
    } else {
        echo "Please provide a valid price.";
    }
}

// Fetch all items from the database to display
$sql = "SELECT * FROM items";
$stmt = $conn->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="path_to_your_stylesheet.css"> <!-- External CSS file -->
</head>
<body>
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
    max-width: 1200px;
    margin: 0 auto;
}

form {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
}

form label {
    font-size: 16px;
    margin-bottom: 5px;
    display: block;
    color: #333;
}

form input, form textarea, form button {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 16px;
    box-sizing: border-box;
}

form textarea {
    resize: vertical;
    height: 150px;
}

form input[type="file"] {
    padding: 5px;
}

form button {
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
    color: #333;
}

th {
    background-color: #007bff;
    color: white;
}

td img {
    width: 80px;
    height: auto;
    border-radius: 5px;
}

.actions a {
    margin-right: 15px;
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
    transition: color 0.3s ease;
}

.actions a:hover {
    color: #0056b3;
}

footer {
    text-align: center;
    padding: 10px;
    background-color: #007bff;
    color: white;
    position: fixed;
    width: 100%;
    bottom: 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    form input, form textarea, form button {
        padding: 10px;
        font-size: 14px;
    }

    table th, table td {
        font-size: 14px;
    }

    td img {
        width: 60px;
    }
    </style>

<nav>
    <div class="logo">
        <div class="logo">Barangay Rental System</div> <!-- Adjust logo size -->
    </div>
    <div class="nav-links">
        <a href="../dashboard/admin_dashboard.php">Dashboard</a>
        <a href="../items/add_item.php">Add Items</a>
        <a href="../reservations/view_reservations.php">View Reservations</a>
        <a href="../reservations/payment_record.php">Payment Record</a>
        <a href="../reservations/admin_notification.php">Notification</a>
        <a href="../dashboard/manage_user.php">Users Account</a>
        <a href="../logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Add New Item</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Item Name:</label>
        <input type="text" name="name" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label for="image">Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Item</button>
    </form>

    <h3>Existing Items</h3>
    <?php if (count($items) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id']) ?></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= htmlspecialchars(number_format($item['price'], 2)) ?></td>
                        <td>
                            <?php if ($item['image']): ?>
                                <img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <?php else: ?>
                                No image available
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="update_item.php?id=<?= $item['id'] ?>">Update</a>
                            <a href="delete_item.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No items added yet.</p>
    <?php endif; ?>
</div>

<!--<footer>
    <p>&copy; 2024 Barangay Community Rental System</p>
</footer>-->

</body>
</html>

