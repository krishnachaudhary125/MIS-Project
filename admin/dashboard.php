<?php
include "header.php";
if (!isset($_GET['home']) && !isset($_GET['product'])):
?>

<div class="dashboard-body">
    <div class="dashboard-sidebar">
        <?php include 'sidebar.php'; ?>
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