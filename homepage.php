<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Rental System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        /* Navbar */
.navbar {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.navbar .logo {
    font-size: 24px;
    font-weight: bold;
}

.navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar ul li {
    margin-left: 20px;
}

.navbar ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;
}

.navbar ul li a:hover {
    text-decoration: underline;
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}


        /* Hero Section */
        .hero {
            text-align: center;
            padding: 250px 20px;
            background-image: url('images/e22eb1253be3e5cd9e997cc4989e89d9.gif');
            background-size: cover;
            background-position: center;
            color: white;
            box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
        }

        .hero h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .hero a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .hero a:hover {
            background-color: #0056b3;
        }

        /* Sections */
        /*section {
            padding: 245px 20px;
            max-width: 1300px;
            margin: 0 auto;
        }*/

        #items, #about, #contact, #developer {
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        #items {
            background-image: url('images/background2.jpg');
text-align: center;
            padding: 90px 0px;
            background-size: cover;
            background-position: center;
            color: white;
            box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
        }

        #about {
            background-image: url('images/background3.jpg');
text-align: center;
            padding: 240px 20px;
            background-size: cover;
            background-position: center;
            color: white;
            box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
        }

        #contact {
            background-image: url('images/background4.jpg');
            text-align: center;
            padding: 240px 20px;
            background-size: cover;
            background-position: center;
            color: white;
            box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
        }
        

        #developer {
            background-image: url('path/to/developer-background.jpg');
            text-align: center;
            padding: 240px 20px;
            background-size: cover;
            background-position: center;
            color: white;
            box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
        }

        section h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        section p {
            text-align: center;
            font-size: 16px;
            line-height: 1.6;
        }

/* Item Grid Layout */
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

/* Item Card Style */
.item-card {
    background-color: white;
    border-radius: 10px; /* Slightly rounded corners for a smoother look */
    padding: 1px; /* Add padding for more space */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    text-align: center;
    color: #333;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden; /* Ensure elements don't overflow the card */
}

/* Hover effect for the card */
.item-card:hover {
    transform: translateY(-5px); /* Card slightly lifts up on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* Stronger shadow on hover */
}

/* Image Styling */
.item-card img {
    width: 100%; /* Slightly smaller image for a more balanced look */
    height: auto; /* Maintain aspect ratio */
    max-height: 250px; /* Limit the height to prevent images from becoming too large */
    border-radius: 8px; /* Rounded corners for the image */
    margin-bottom: 15px; /* Space below the image */
    object-fit: cover; /* Ensure the image covers the area without distortion */
    transition: transform 0.3s ease;
}

/* Hover effect for the image */
.item-card img:hover {
    transform: scale(1.05); /* Slight zoom effect when hovering over the image */
}

/* Item Title */
.item-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333; /* Dark color for the title */
}

/* Item Description */
.item-card p {
    font-size: 14px;
    color: #777; /* Lighter color for the description */
    margin-bottom: 15px; /* Space before the link */
}

/* Link Button */
.item-card a {
    display: inline-block;
    padding: 10px 15px;
    background-color: #007bff; /* Blue background for the button */
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

/* Hover effect for the link button */
.item-card a:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: translateY(-3px); /* Button lifts slightly on hover */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .items-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Adjust grid for smaller screens */
    }
}

@media (max-width: 480px) {
    .item-card img {
        width: 80%; /* Slightly larger images for very small screens */
        max-height: 150px; /* Adjust the maximum height for smaller screens */
    }
}



        /* Developer Section */
#developer {
    background-image: url('images/itachi-uchiha-anime.gif'); /* Replace with your desired background */
    padding: 200px 20px;
    text-align: center;
    color: white;
    box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.7);
}

#developer h2 {
    font-size: 32px;
    margin-bottom: 20px;
}

.developer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

/* Developer Card */
.developer-card {
    background-image: url('images/itachi-uchiha-anime.gif'); /* Replace with your desired GIF or video path */
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Keeps the background fixed during scroll */
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    color: white; /* Set text color to white for better visibility */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    backdrop-filter: blur(2px); /* Adds the blurriness effect */
    overflow: hidden;
}

.developer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

/* Vibrant and deep colors for text */
.developer-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid #FF6347; /* Vibrant border color */
}

.developer-card h3 {
    font-size: 22px;
    margin-bottom: 5px;
    color: #FF6347; /* Vibrant deep red color */
}

.developer-card p {
    font-size: 14px;
    color: #FFD700; /* Golden color for the text */
    margin-bottom: 10px;
}

.developer-card p:last-child {
    font-size: 12px;
    color: #ADFF2F; /* Deep green color for the last text */
}

/* Optional: Apply more color and contrast for readability */
.developer-card {
    color: #fff;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay for better text visibility */
}

.developer-card h3,
.developer-card p {
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4); /* Adds shadow to the text for more contrast */
}




        /* Footer */
        .footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 0px;
        }

        .footer p {
            margin: 0;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: none;
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
    background-color: rgba(0, 0, 0, 0.8); /* Black background with transparency */
    align-items: center;
    justify-content: center;
}

.modal-content {
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
    animation: zoomIn 0.3s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
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

/* Animation for modal appearance */
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
    <div class="logo">
        <img src="images/DALL_E_2024-11-26_08.16.35_-_A_sleek_and_modern_logo_design_for_a__Community_Rental_System_._The_design_features_a_circular_badge_layout_with_a_vibrant_blue_and_green_gradient_bac-removebg-preview.png" alt="Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;">
        Barangay Rental System
    </div>
    <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#items">Our Items</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="#developer">Developer</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</div>


    <!-- Hero Section -->
    <div class="hero" id="home">
        <h1>Welcome to the Barangay Rental System</h1>
        <p>Your trusted platform for renting barangay equipment and facilities.</p>
        <a href="login.php">Get Started</a>
    </div>

    <!-- Our Items Section -->
    <section id="items">
        <h2>Our Items</h2>
        <p>Explore a variety of items and facilities available for rent in your barangay.</p>
        <div class="items-grid">
            <div class="item-card">
                <img src="images/item1.jpg" alt="Item 1">
                <h3>Wedding Chair</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item2.jpg" alt="Item 2">
                <h3>Weeding Table</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item4.jpg" alt="Item 2">
                <h3>Monoblock Chair</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item3.jpg" alt="Item 2">
                <h3>Truck Service</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item5.jpg" alt="Item 2">
                <h3>Tricycle</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item6.jpg" alt="Item 2">
                <h3>Electric Scooter</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item7.jpg" alt="Item 2">
                <h3>Remote Controll Wheelchair</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
            <div class="item-card">
                <img src="images/item8.jpg" alt="Item 2">
                <h3>Occassion Table</h3>
                <p>Available</p>
                <a href="login.php">Rent Now</a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about">
        <h2>About Us</h2>
        <p>
            The Barangay Rental System was designed to make renting barangay facilities 
            and equipment easier for residents.
        </p>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <h2>Contact Us</h2>
        <p>For inquiries, you can reach us via email at barangayrental@example.com or call us at (123) 456-7890.</p>
    </section>

        <!-- Developer Section -->
    <section id="developer">
        <h2>Meet the Developers</h2>
        <div class="developer-grid">
            <div class="developer-card">
                <img src="images/developer1.jpg" alt="Developer 1">
                </h3><a href="https://www.facebook.com/john.lance.1806253"><h3>John Lance Llames</h3></a>
                <p>Hacker</p>
                <p>Specializes in backend development and system architecture. Ensures system reliability and performance.</p>
            </div>
            <div class="developer-card">
                <img src="images/developer2.jpg" alt="Developer 2">
                </h3><a href="https://www.facebook.com/earonjay.celestepatron.9"><h3>Earon Jay Patron</h3></a>
                <p>Hacker</p>
                <p>Focuses on creating user-friendly designs with a seamless experience for the end-users.</p>
            </div>
            <div class="developer-card">
                <img src="images/developer3.jpg" alt="Developer 3">
                <a href="https://www.facebook.com/profile.php?id=100088718572634"><h3>Lumbo Bañes Jhasmin</h3></a>
                <p>Hustler</p>
                <p>Builds responsive and interactive web interfaces, ensuring compatibility across all devices.</p>
            </div>
            <div class="developer-card">
                <img src="images/developer4.jpg" alt="Developer 4">
                </h3><a href="https://www.facebook.com/profile.php?id=100072599049939"><h3>Ma.Teresa Manguda</h3></a>
                <p>Hipster</p>
                <p>Builds responsive and interactive web interfaces, ensuring compatibility across all devices.</p>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Barangay Rental System. All rights reserved. | <a href="#home">Back to Top</a> | <a href="https://www.facebook.com/john.lance.1806253">Contact Me</a></p>
    </div>

    <!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close" id="closeModal">&times;</span>
    <img class="modal-content" id="modalImage" alt="Developer">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');

        // Add click event to all developer images
        const developerImages = document.querySelectorAll('.developer-card img');
        developerImages.forEach(img => {
            img.addEventListener('click', function () {
                modal.style.display = 'flex'; // Show modal
                modalImg.src = this.src; // Set modal image source to clicked image
                modalImg.alt = this.alt || 'Developer Image'; // Set alt text for accessibility
            });
        });

        // Close modal on close button click
        closeModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        // Close modal on clicking outside the modal content
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
