<?php
require '../database/config.php';
session_start();

if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];
    $sql = "DELETE FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id]);
    header('Location: notifications.php');
}
?>
