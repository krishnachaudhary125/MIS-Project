<?php
session_start();
include '../../Database/connection.php';

// Get logged-in admin info
$logged_in_admin_id = $_SESSION['admin_id'] ?? null;

if (!$logged_in_admin_id) {
    echo "<script>alert('You must be logged in.');</script>";
    echo "<script>window.location.href = '../login.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('No user ID specified.');</script>";
    echo "<script>window.location.href = '../dashboard.php?user';</script>";
    exit();
}

$user_id_to_delete = intval($_GET['id']);

// Prevent deleting the logged-in admin by mistake
if ($user_id_to_delete === $logged_in_admin_id) {
    echo "<script>alert('You cannot delete the logged-in admin.');</script>";
    echo "<script>window.location.href = '../dashboard.php?user';</script>";
    exit();
}

// Delete user
$delete_query = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($delete_query);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id_to_delete);

if ($stmt->execute()) {
    echo "<script>alert('User deleted successfully.');</script>";
    echo "<script>window.location.href = '../dashboard.php?user';</script>";
} else {
    echo "<script>alert('Error deleting user.');</script>";
    echo "<script>window.location.href = '../dashboard.php?user';</script>";
}

$stmt->close();
?>