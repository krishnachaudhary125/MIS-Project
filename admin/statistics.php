<?php
include '../Database/connection.php';

if (!isset($_SESSION['role']) ||$_SESSION['role'] !== 'owner' || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized page!');</script>";
    echo "<script>window.location.href = '../user/index.php';</script>";
    exit();
}

// Helper function to get count
function getCount($conn, $table, $column) {
    $query = "SELECT COUNT($column) AS total FROM $table";
    $result = mysqli_query($conn, $query);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['total'];
    }
    return 0;
}

// Helper function to get sum
function getSum($conn, $table, $column) {
    $query = "SELECT SUM($column) AS total FROM $table";
    $result = mysqli_query($conn, $query);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['total'] ?? 0;
    }
    return 0;
}
?>

<div class="box-container">

    <!-- Admin Count -->
    <div class="box box1">
        <div class="text">
            <h2 class="topic-heading"><?= getCount($conn, 'admin', 'admin_id'); ?></h2>
            <h2 class="topic">No. of Admin</h2>
        </div>
        <i class="fa fa-user-shield fa-3x"></i>
    </div>

    <!-- User Count -->
    <div class="box box2">
        <div class="text">
            <h2 class="topic-heading"><?= getCount($conn, 'users', 'user_id'); ?></h2>
            <h2 class="topic">No. of Users</h2>
        </div>
        <i class="fa fa-users fa-3x"></i>
    </div>

    <!-- Category Count -->
    <div class="box box3">
        <div class="text">
            <h2 class="topic-heading"><?= getCount($conn, 'category', 'category_id'); ?></h2>
            <h2 class="topic">No. of Categories</h2>
        </div>
        <i class="fa fa-list-alt fa-3x"></i>
    </div>

    <!-- Product Count -->
    <div class="box box4">
        <div class="text">
            <h2 class="topic-heading"><?= getCount($conn, 'product', 'product_id'); ?></h2>
            <h2 class="topic">No. of Products</h2>
        </div>
        <i class="fa fa-boxes fa-3x"></i>
    </div>

    <!-- Total Revenue -->
    <!-- <div class="box box5">
        <div class="text">
            <h2 class="topic-heading"><?= getSum($conn, 'orders', 'total_amount'); ?></h2>
            <h2 class="topic">Total Revenue</h2>
        </div>
        <i class="fa fa-dollar-sign fa-3x"></i>
    </div> -->

</div>

<?php
include "footer.php";
?>