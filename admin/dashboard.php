<?php
include "header.php";
if (!isset($_GET['home']) && !isset($_GET['product'])):
?>

<div class="dashboard-body">
    <div class="dashboard-sidebar">
        <nav class="sidebar-nav">
            <div class="option">
                <ul>
                    <li>
                        <a href="dashboard.php?statistics">Statistics <i class="fa fa-bar-chart"></i></a>
                    </li>
                    <li>
                        <a href="dashboard.php?admin">Admin <i class="fa fa-user-secret"></i></a>
                    </li>
                    <li>
                        <a href="dashboard.php?user">User <i class="fa fa-user"></i></a>
                    </li>
                    <li>
                        <a href="dashboard.php?category">Category <i class="fa fa-tags"></i></a>
                    </li>
                    <li>
                        <a href="dashboard.php?add_product">Product <i class="fa fa-cube"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="dashboard-main">
        <?php
        if (isset($_GET['statistics'])) {
            include 'statistics.php';
        }
        ?>
        <?php
        if (isset($_GET['admin'])) {
            include 'admin.php';
        }
        ?>
        <?php
        if (isset($_GET['user'])) {
            include 'user.php';
        }
        ?>
        <?php
        if (isset($_GET['category'])) {
            include 'category.php';
        }
        ?>
        <?php
        if (isset($_GET['add_product'])) {
            include 'add_product.php';
        }
        ?>
    </div>
</div>

<?php
endif;
include "footer.php";
?>