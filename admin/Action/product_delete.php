<?php
include '../../Database/connection.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Check if product exists
    $check_query = "SELECT COUNT(*) AS product_count FROM product WHERE product_id = ?";
    $check_stmt = $conn->prepare($check_query);

    if ($check_stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();

    if ($row['product_count'] == 0) {
        echo "<script>alert('Product not found.');</script>";
        echo "<script>window.location.href = '../dashboard.php?add_products';</script>";
        exit();
    }

    // Proceed with deletion
    $delete_query = "DELETE FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting product: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    echo "<script>window.location.href = '../dashboard.php?add_products';</script>";
} else {
    echo "<script>alert('No product ID specified.');</script>";
    echo "<script>window.location.href = '../dashboard.php?add_products';</script>";
}
?>