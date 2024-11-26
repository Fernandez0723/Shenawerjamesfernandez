<?php
session_start();
require '../database/config.php';

// Redirect if the user is not a resident
if ($_SESSION['role'] != 'resident') {
    header('Location: ../login.php');
    exit();
}

// Fetch items including quantity, price, and image
$sql = "SELECT * FROM items";
$stmt = $conn->query($sql);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Items</title>
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

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .item-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 15px;
            text-align: center;
        }

        .item-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .item-card h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .item-card p {
            margin: 5px 0;
            color: #666;
        }

        .item-card .price {
            color: #007bff;
            font-weight: bold;
        }

        .item-card .availability {
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            background-color: #007bff;
            color: white;
        }

        /* Welcome Banner Styling */
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

/* Text scrolling animation */
@keyframes scrollText {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

/* Modal styling */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
}

.modal-content {
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
    animation: zoomIn 0.3s;
}

.close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    text-shadow: 2px 2px 4px #000;
}

/* Animation for modal */
@keyframes zoomIn {
    from {
        transform: scale(0.5);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
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

<!-- Content Container -->
<div class="container">
    <h1>Welcome, Resident!</h1>
    <h2>Available Items to Rent</h2>

    <div class="welcome-banner">
    <p id="welcome-message">Welcome, Resident!</p>
</div>
    
    <!-- Items Grid -->
    <div class="items-grid">
        <?php foreach ($items as $item): ?>
        <div class="item-card">
            <img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <h3><?= htmlspecialchars($item['name']) ?></h3>
            <p><?= htmlspecialchars($item['description']) ?></p>
            <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
            <p class="price">₱<?= number_format($item['price'], 2) ?></p>
            <p class="availability">
                <?= $item['quantity'] > 0 ? "Available" : "Unavailable" ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; <?= date('Y') ?> Barangay Rental System</p>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close" id="closeModal">&times;</span>
    <img class="modal-content" id="modalImage">
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messages = [
            "Welcome, Resident!",
            "Enjoy Your Rental Experience!",
            "Check Out the Latest Items!",
            "Contact Admin for Assistance!",
            "Huwag Mahiya mag Rent, Pogi ang Mga Admin!",
            "Shout-out kay Maam Dianne Christine Bago, Pasado po sana kami huhuhhu",
        ];
        let index = 0;

        const messageElement = document.getElementById('welcome-message');

        // Change the message every 10 seconds to match animation duration
        setInterval(() => {
            index = (index + 1) % messages.length;
            messageElement.textContent = messages[index];
        }, 10000); // Change every 10 seconds
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');

        // Add click event to each image
        const images = document.querySelectorAll('.item-card img');
        images.forEach(image => {
            image.addEventListener('click', function () {
                modal.style.display = 'flex'; // Show modal
                modalImg.src = this.src; // Set the modal image source to clicked image
                modalImg.alt = this.alt; // Set alt text for accessibility
            });
        });

        // Close modal when close button is clicked
        closeModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Close modal when clicking outside the image
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>



</body>
</html>
