<?php
include "../database/connection.php";

if (!isset($_SESSION['role']) ||$_SESSION['role'] !== 'owner' || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized page!');</script>";
    echo "<script>window.location.href = '../user/index.php';</script>";
    exit();
}

// Get logged-in admin info
$logged_in_admin_id = $_SESSION['admin_id'] ?? null;
$logged_in_admin_role = isset($_SESSION['role']) ? strtolower(trim($_SESSION['role'])) : null;

if (!$logged_in_admin_id) {
    echo "<script>alert('You must be logged in.');</script>";
    echo "<script>window.location.href = '../login.php';</script>";
    exit();
}

// Add Admin - only owner
if (isset($_POST['submit'])) {
    if ($logged_in_admin_role !== 'owner') {
        echo "<script>alert('Only an owner can add a new admin.');</script>";
        exit();
    }

    $name = trim($_POST['adminName']);
    $phone = trim($_POST['adminPhone']);
    $email = trim($_POST['adminEmail']);
    $password = trim($_POST['adminPsw']);
    $cpassword = trim($_POST['adminCpsw']);

    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($cpassword)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif ($password !== $cpassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $select_query = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($select_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('This email is already registered.');</script>";
        } else {
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO admin (fullname, phone, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssss", $name, $phone, $email, $hashed_password);
            if ($stmt->execute()) {
                echo "<script>alert('Admin added successfully.'); window.location.href=window.location.href;</script>";
            } else {
                echo "<script>alert('Error adding admin.');</script>";
            }
        }
        $stmt->close();
    }
}

// Delete Admin - only owner
if (isset($_GET['delete_id'])) {
    if ($logged_in_admin_role !== 'owner') {
        echo "<script>alert('Only an owner can delete an admin.');</script>";
        exit();
    }

    $delete_id = intval($_GET['delete_id']);

    // Prevent owner from deleting themselves
    if ($delete_id === $logged_in_admin_id) {
        echo "<script>alert('You cannot delete your own account.');</script>";
        exit();
    }

    // Prevent deleting other owners
    $owner_check_query = "SELECT role FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($owner_check_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row && strtolower($row['role']) === 'owner') {
        echo "<script>alert('Cannot delete another owner.');</script>";
        exit();
    }
    $stmt->close();

    $delete_query = "DELETE FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Admin deleted successfully.'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Error deleting admin.');</script>";
    }
    $stmt->close();
}
?>

<!-- Add Admin Popup -->
<div class="popup-category" id="addAdminPopup">
    <div class="add-category-popup">
        <div class="add-category-header">
            <h1 class="add-category-title">Add Admin</h1>
            <span class="close-btn" onclick="closeAdminPopup()">&times;</span>
        </div>
        <div class="category-popup-body">
            <form method="post">
                <div class="add-category-field">
                    <input type="text" name="adminName" placeholder="Full Name" required>
                </div>
                <div class="add-category-field">
                    <input type="text" name="adminPhone" placeholder="Phone No." required>
                </div>
                <div class="add-category-field">
                    <input type="email" name="adminEmail" placeholder="E-Mail" required>
                </div>
                <div class="add-category-field">
                    <input type="password" name="adminPsw" placeholder="Password" required>
                </div>
                <div class="add-category-field">
                    <input type="password" name="adminCpsw" placeholder="Confirm Password" required>
                </div>
                <div class="add-category-button">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Admin Table -->
<div class="category-container">
    <div class="category-main">
        <div class="category-header">
            <h1 class="category-title">Admin</h1>
            <?php if ($logged_in_admin_role === 'owner'): ?>
            <button type="button" class="category-popup-button" onclick="openAdminPopup()">Add Admin</button>
            <?php endif; ?>
        </div>

        <div class="admin-body">
            <div class="category-data">
                <table>
                    <thead>
                        <tr>
                            <th class="thsno">S.No.</th>
                            <th class="tdcategory">Full Name</th>
                            <th class="tdcategory">Phone No.</th>
                            <th class="tdcategory">E-Mail</th>
                            <th class="thaction">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $select_admin = "SELECT * FROM admin";
                        $admin_select = mysqli_query($conn, $select_admin);
                        while ($row = mysqli_fetch_assoc($admin_select)):
                            $i++;
                        ?>
                        <tr>
                            <td class="tdsno"><?= $i ?>.</td>
                            <td class="tdcategory"><?= htmlspecialchars($row['fullname']) ?></td>
                            <td class="tdcategory"><?= htmlspecialchars($row['phone']) ?></td>
                            <td class="tdcategory"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="tdaction">
                                <?php 
                                if ($logged_in_admin_role === 'owner' && $row['admin_id'] !== $logged_in_admin_id && strtolower($row['role']) !== 'owner'): ?>
                                <button type="button" onclick="deleteAdmin(<?= $row['admin_id']; ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <?php else: ?>
                                <span style="color:#888;">No permission</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function openAdminPopup() {
    document.getElementById("addAdminPopup").style.display = "block";
}

function closeAdminPopup() {
    document.getElementById("addAdminPopup").style.display = "none";
}

function deleteAdmin(id) {
    if (confirm("Are you sure you want to delete this admin?")) {
        window.location.href = "Action/admin_delete.php?id=" + id;
    }
}
</script>