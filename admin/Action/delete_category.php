<?php
include "../../database/connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner' && $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized page!');</script>";
    echo "<script>window.location.href = '../../user/index.php';</script>";
    exit();
}

function redirectWithAlert($message) {
    echo "<script>alert('$message'); window.location.href = '../dashboard.php?category';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']); 

    $check_query = "SELECT COUNT(*) AS product_count FROM product WHERE category_id = ?";
    $check_stmt = $conn->prepare($check_query);

    if ($check_stmt === false) {
        redirectWithAlert("Database error: " . $conn->error);
    }

    $check_stmt->bind_param("i", $category_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();

    if ($row['product_count'] > 0) {
        redirectWithAlert("There is a product with this category, it cannot be deleted.");
    }

    $delete_query = "DELETE FROM category WHERE category_id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt === false) {
        redirectWithAlert("Database error: " . $conn->error);
    }

    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        redirectWithAlert("Category deleted successfully.");
    } else {
        redirectWithAlert("Error deleting category.");
    }

    $stmt->close();

} else {
    redirectWithAlert("No category ID specified.");
}
?>