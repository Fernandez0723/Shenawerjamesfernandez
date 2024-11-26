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

// Fetch item to delete the image
$sql = "SELECT image FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Delete the image file if it exists
if ($item && $item['image']) {
    $image_path = '../' . $item['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete the item
$sql = "DELETE FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

header('Location: add_item.php?message=Item+deleted+successfully');
exit();
?>
