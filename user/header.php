<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="../photos/main-logo.png" alt="Logo" onclick="location.href='index.php'">
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="product.php">Product</a></li>
            </ul>
            <div class="search">
                <input type="text" value="" placeholder="Search...">
                <button type="submit"><i class="fa fa-search"></i></button>
                <button onclick="location.href='index.php'"><i class="fa fa-shopping-cart"></i></button>
                <button onclick="togglePopup()"><i class="fa fa-user"></i></button>
            </div>
        </nav>
    </header>
    <div id="accountPopup" class="popup-sign">
        <div class="popup-content">
            <ul>
                <?php if (isset($_SESSION['user_name'])): ?>
                <li>
                    <h3><strong><?php echo $_SESSION['user_name']; ?></strong></h3>
                </li>
                <div class="profile">
                    <li><a href="logout.php">Log Out<i class="fa fa-sign-out"></i></a></li>
                </div>
                <?php else: ?>
                <li><a href="login.php">Sign In<i class="fa fa-sign-in"></i></a></li>
                <li><a href="register.php">Sign Up<i class="fa fa-user-plus"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="main-container">

        <script>
        function togglePopup() {
            const popup = document.getElementById('accountPopup');
            if (popup.style.display === 'flex') {
                popup.style.display = 'none';
            } else {
                popup.style.display = 'flex';
            }
        }

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