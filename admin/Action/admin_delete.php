<?php
session_start();
include '../../Database/connection.php';

// Get logged-in admin info
$logged_in_admin_id = $_SESSION['admin_id'] ?? null;
$logged_in_admin_role = strtolower(trim($_SESSION['role'] ?? ''));

if (!$logged_in_admin_id) {
    echo "<script>alert('You must be logged in.');</script>";
    echo "<script>window.location.href = '../login.php';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('No admin ID specified.');</script>";
    echo "<script>window.location.href = '../dashboard.php?admin';</script>";
    exit();
}

$admin_id_to_delete = intval($_GET['id']);

// Debug: check role
// echo "Role: " . $logged_in_admin_role; exit();

// Only owner can delete admins
if ($logged_in_admin_role !== 'owner') {
    echo "<script>alert('Only an owner can delete admins.');</script>";
    echo "<script>window.location.href = '../dashboard.php?admin';</script>";
    exit();
}

// Prevent owner from deleting themselves
if ($admin_id_to_delete === $logged_in_admin_id) {
    echo "<script>alert('You cannot delete your own account.');</script>";
    echo "<script>window.location.href = '../dashboard.php?admin';</script>";
    exit();
}

// Prevent deleting another owner
$stmt = $conn->prepare("SELECT role FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id_to_delete);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && strtolower(trim($row['role'])) === 'owner') {
    echo "<script>alert('Cannot delete another owner.');</script>";
    echo "<script>window.location.href = '../dashboard.php?admin';</script>";
    exit();
}

// Delete admin
$delete_query = "DELETE FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $admin_id_to_delete);

if ($stmt->execute()) {
    echo "<script>alert('Admin deleted successfully.');</script>";
    echo "<script>window.location.href = '../dashboard.php?admin';</script>";
} else {
    echo "<script>alert('Error deleting admin.');</script>";
    echo "<script>window.location.href = '../dashboard.php?admin';</script>";
}

$stmt->close();
?>