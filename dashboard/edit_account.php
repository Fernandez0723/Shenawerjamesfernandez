<?php
require '../database/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $profile_pic = $_FILES['profile_pic']['name'];

    if (!empty($profile_pic)) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($profile_pic);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);

        $sql = "UPDATE users SET username = ?, fullname = ?, contact_number = ?, address = ?, status = ?, profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $fullname, $contact_number, $address, $status, $profile_pic, $user_id]);
    } else {
        $sql = "UPDATE users SET username = ?, fullname = ?, contact_number = ?, address = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $fullname, $contact_number, $address, $status, $user_id]);
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href='account_settings.php';</script>";
    exit();
}

// Fetch user details
$sql = "SELECT username, fullname, contact_number, address, profile_picture, status FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        /* Styling similar to the Account Settings page */
                .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        form input[type="text"], form textarea {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>

            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" id="contact_number" value="<?= htmlspecialchars($user['contact_number']) ?>" required>

            <label for="address">Address</label>
            <textarea name="address" id="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>

            <label for="status">Status</label>
            <input type="text" name="status" id="status" value="<?= htmlspecialchars($user['status']) ?>" required>

            <label for="profile_pic">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
