<?php
require 'database/config.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation for empty fields
    if (empty($email) || empty($password)) {
        $error_message = "Email and password are required.";
    } else {
        // Fetch user by email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check if the user is blocked
            if ($user['status'] == 'blocked') {
                $error_message = "Your account is blocked. Please contact the administrator.";
            } else {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Store user data in session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on user role
                    if ($_SESSION['role'] == 'admin') {
                        header('Location: dashboard/admin_dashboard.php'); // Correct admin dashboard path
                    } elseif ($_SESSION['role'] == 'resident') {
                        header('Location: dashboard/resident_dashboard.php'); // Correct resident dashboard path
                    } else {
                        // Redirect to a fallback page for undefined roles
                        $error_message = "Your role is not recognized. Please contact the administrator.";
                    }
                    exit();
                } else {
                    $error_message = "Incorrect password.";
                }
            }
        } else {
            $error_message = "No user found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('images/e22eb1253be3e5cd9e997cc4989e89d9.gif') no-repeat center center fixed; /* Replace with your GIF's path */
            background-size: cover;
        }

        .container {
    width: 100%;
    max-width: 400px;
    padding: 30px;
    background-color: rgba(255, 255, 255, 0.3); /* More transparent background */
    border-radius: 10px; /* Smooth corners */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* Subtle shadow for separation */
    backdrop-filter: blur(4px); /* Stronger blur effect for frosted glass look */
    -webkit-backdrop-filter: blur(15px); /* Ensure compatibility with Safari */
    border: 1px solid rgba(255, 255, 255, 0.4); /* Optional border for better visibility */
}


        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 100px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
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
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #ff0000;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-link a {
            text-decoration: none;
            color: #007bff;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="images/DALL_E_2024-11-26_08.21.10_-_A_vibrant_and_colorful_circular_logo_design_for_a__Community_Rental_System___featuring_a_stylish__bold_font_with_gradient_effects_in_shades_of_blue__g-removebg-preview.png" alt="Barangay Rental System Logo"> <!-- Replace with the actual logo path -->
    </div>

    <h2>Login</h2>

    <?php
    if (isset($error_message)) {
        echo "<div class='error'>$error_message</div>";
    }
    ?>

    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p>Return to Homepage <a href="homepage.php">Click Here</a></p>
    </div>
</div>

</body>
</html>
