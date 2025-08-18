<?php
include '../database/connection.php';

if (!isset($_SESSION['role']) ||$_SESSION['role'] !== 'owner' || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized page!');</script>";
    echo "<script>window.location.href = '../user/index.php';</script>";
    exit();
}

?>
<div class="category-container">
    <div class="category-main">
        <div class="category-header">
            <h1 class="category-title">Users</h1>
        </div>

        <div class="user-body">
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
                        $select_users = "SELECT * FROM users";
                        $user_select = mysqli_query($conn, $select_users);
                        while ($row_data = mysqli_fetch_assoc($user_select)):
                            $i++;
                        ?>
                        <tr>
                            <td class="tdsno"><?= $i ?>.</td>
                            <td class="tdcategory"><?= htmlspecialchars($row_data['fullname']) ?></td>
                            <td class="tdcategory"><?= htmlspecialchars($row_data['phone']) ?></td>
                            <td class="tdcategory"><?= htmlspecialchars($row_data['email']) ?></td>
                            <td class="tdaction">
                                <button type="button" onclick="deleteUser(<?= $row_data['user_id']; ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
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
function deleteUser(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = "./Action/user_delete.php?id=" + id;
    }
}
</script>