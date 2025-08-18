<?php 
session_start();

if (!isset($_SESSION['role']) ||$_SESSION['role'] !== 'owner' && $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized page!');</script>";
    echo "<script>window.location.href = '../user/index.php';</script>";
    exit();
}

include "../function/home_function.php";
include "../function/product_function.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../user/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="../photos/main-logo.png" alt="Logo" onclick="location.href='index.php'">
            </div>
            <ul>
                <li><a href="dashboard.php?home">Home</a></li>
                <li><a href="dashboard.php?product">Product</a></li>
                <li><a href="dashboard.php?statistics">Dashboard</a></li>
            </ul>
            <div class="search">
                <button onclick="togglePopup()"><i class="fa fa-user-secret"></i></button>
            </div>
        </nav>
    </header>
    <div id="accountPopup" class="popup-sign">
        <div class="popup-content">
            <ul>
                <?php if (isset($_SESSION['admin_name'])): ?>
                <li>
                    <h3><strong><?php echo $_SESSION['admin_name']; ?></strong></h3>
                </li>
                <div class="profile">
                    <li><a href="../user/logout.php">Log Out<i class="fa fa-sign-out"></i></a></li>
                </div>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="main-container">
        <?php
        if (isset($_GET['home'])) {
            home();
        }
        ?>
        <?php
        if (isset($_GET['product'])) {
            product();
        }
        ?>

        <script>
        function togglePopup() {
            const popup = document.getElementById('accountPopup');
            if (popup.style.display === 'flex') {
                popup.style.display = 'none';
            } else {
                popup.style.display = 'flex';
            }
        }

        // Close the popup when clicking outside the content
        window.onclick = function(event) {
            const popup = document.getElementById('accountPopup');
            const content = document.querySelector('.popup-content');
            if (event.target === popup && !content.contains(event.target)) {
                popup.style.display = 'none';
            }
        };
        const container = document.querySelector('.main-container');
        let scrollTimeout;

        container.addEventListener('scroll', () => {
            container.classList.add('scrolling');

            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                container.classList.remove('scrolling');
            }, 500); // Revert after 500ms of inactivity
        });
        </script>