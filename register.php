<?php
require 'database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $username = $_POST['username'];
    $address = $_POST['address'];

    // Handle profile picture upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
    }

    // Insert data into the database
    $sql = "INSERT INTO users (fullname, email, password, role, username, address, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fullname, $email, $password, $role, $username, $address, $profile_picture]);

    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://media.giphy.com/media/xT9IgzoKnwFNmISR8I/giphy.gif') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 90%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 0 35px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .already-account {
            text-align: center;
            margin-top: 15px;
        }

        .already-account a {
            color: #007bff;
            text-decoration: none;
        }

        .already-account a:hover {
            text-decoration: underline;
        }

        .profile-picture-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 10px auto;
            display: block;
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 18px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="resident">Resident</option>
                <option value="admin">Admin</option>
            </select>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            <img id="profile_picture_preview" class="profile-picture-preview" src="#" alt="Profile Picture Preview" style="display:none;">

            <button type="submit">Register</button>
        </form>
        <div class="already-account">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
        <div class="already-account">
            <p>Contact Developer? <a href="https://www.facebook.com/search/top?q=john%20lance%20llames">Click Here.</a></p>
        </div>
    </div>

    <script>
        // Preview profile picture on file input change
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile_picture_preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
