<?php
session_start();
require '../database/config.php'; // Include your database connection

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch the total users, total sales, total items, total customers, total admins
try {
    // Total Users
    $userQuery = "SELECT COUNT(*) AS total_users FROM users";
    $userStmt = $conn->query($userQuery);
    $totalUsers = $userStmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Total Sales (Payments)
    $salesQuery = "SELECT SUM(amount) AS total_sales FROM payments";
    $salesStmt = $conn->query($salesQuery);
    $totalSales = $salesStmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

    // Total Items
    $itemQuery = "SELECT COUNT(*) AS total_items FROM items";
    $itemStmt = $conn->query($itemQuery);
    $totalItems = $itemStmt->fetch(PDO::FETCH_ASSOC)['total_items'];

   //Fetch the total count of residents
$sql_residents = "SELECT COUNT(*) AS total_residents FROM users WHERE role = 'resident'";
$stmt_residents = $conn->prepare($sql_residents);
$stmt_residents->execute();
$total_residents = $stmt_residents->fetch(PDO::FETCH_ASSOC)['total_residents'];

    // Total Admins (assuming they are users with 'admin' role)
    $adminQuery = "SELECT COUNT(*) AS total_admins FROM users WHERE role = 'admin'";
    $adminStmt = $conn->query($adminQuery);
    $totalAdmins = $adminStmt->fetch(PDO::FETCH_ASSOC)['total_admins'];

} catch (PDOException $e) {
    die("Error fetching statistics: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }
        h1 {
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
        }
        .welcome-banner {
            position: relative;
            width: 100%;
            background-color: none;
            color: red;
            overflow: hidden;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        .welcome-banner p {
            position: absolute;
            white-space: nowrap;
            animation: scrollText 10s linear infinite;
        }
        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
        .card {
            background-color: white;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: inline-block;
            width: 23%;
            text-align: center;
            cursor: pointer;
        }
        .card h3 {
            margin: 0;
            font-size: 24px;
        }
        .card p {
            font-size: 18px;
            color: #666;
        }
        .card .total {
            font-size: 32px;
            color: #007bff;
            font-weight: bold;
        }
        .card:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        .card-date-time {
            background-color: #f39c12;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: inline-block;
            width: 23%;
            text-align: center;
            cursor: pointer;
        }
        .card-date-time h3 {
            margin: 0;
            font-size: 24px;
        }
        .card-date-time .total {
            font-size: 32px;
            color: #fff;
            font-weight: bold;
        }
        .chart-container {
            margin-top: 40px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            margin: 20px auto;
        }
        /* Print Button Styling */
        .print-btn {
            position: fixed;
            top: 40px;
            right: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            z-index: 1000;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Barangay Rental System</div>
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

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print()">Print</button>

    <div class="container">
        <h1>Welcome, Admin!</h1>

        <div class="welcome-banner">
            <p id="welcome-message">Welcome to the Barangay Community Rental System | Your dashboard is up to date!</p>
        </div>

        <!-- Cards for Total Users, Total Sales, Total Items, and Date/Time -->
        <div class="card" onclick="showDetails('users')">
            <h3>Total Users</h3>
            <p>Registered Users</p>
            <div class="total"><?= number_format($totalUsers) ?></div>
        </div>
        <div class="card" onclick="showDetails('sales')">
            <h3>Total Sales</h3>
            <p>Total Sales Revenue</p>
            <div class="total">₱<?= number_format($totalSales, 2) ?></div>
        </div>
        <div class="card" onclick="showDetails('items')">
            <h3>Total Items</h3>
            <p>Items Available for Rent</p>
            <div class="total"><?= number_format($totalItems) ?></div>
        </div>
        <div class="card" onclick="showDetails('residents')">
            <h3>Total Customers</h3>
            <p>Customers Registered</p>
            <div class="total"><?= number_format($total_residents) ?></div>
        </div>
        <div class="card" onclick="showDetails('admins')">
            <h3>Total Admins</h3>
            <p>Admins Registered</p>
            <div class="total"><?= number_format($totalAdmins) ?></div>
        </div>

        <!-- New Date and Time Card -->
        <div class="card-date-time">
            <h3>Current Date & Time</h3>
            <div id="date-time" class="total"></div>
        </div>

        <!-- Chart for Total Statistics -->
        <center><h3>Chart</h3></center>
        <div class="chart-container">
            <canvas id="statsChart"></canvas>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const messages = [
            "Welcome to the Barangay Community Rental System!",
            "Your dashboard is up to date!",
            "Manage your community with ease!",
            "Track rentals, payments, and users efficiently!",
            "Thank you for your service to the community!"
        ];
        let index = 0;

        const messageElement = document.getElementById('welcome-message');

        // Change the message every 10 seconds to match animation duration
        setInterval(() => {
            index = (index + 1) % messages.length;
            messageElement.textContent = messages[index];
        }, 10000); // Change every 10 seconds

        // Display Current Date and Time
        function updateDateTime() {
            const now = new Date();
            const dateTimeString = now.toLocaleString(); // Format as per the user's locale
            document.getElementById('date-time').textContent = dateTimeString;
        }

        updateDateTime(); // Initial date-time update
        setInterval(updateDateTime, 1000); // Update every second
    });
    </script>

    <script>
        // Chart Data
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Users', 'Total Sales', 'Total Items', 'Total Customers', 'Total Admins'],
                datasets: [{
                    label: 'Statistics',
                    data: [<?= $totalUsers ?>, <?= $totalSales ?>, <?= $totalItems ?>, <?= $total_residents ?>, <?= $totalAdmins ?>],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    borderColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
