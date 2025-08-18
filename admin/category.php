<?php
include "../database/connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner' ||$_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized page!');</script>";
    echo "<script>window.location.href = '../user/index.php';</script>";
    exit();
}

if (isset($_POST['submit'])) {
    $category = trim($_POST['categoryInput']);
    $category_lower = strtolower($category);

    if (empty($category)) {
        echo "<script>alert('Category input cannot be empty.');</script>";
    } else {
        $select_query = "SELECT * FROM category WHERE LOWER(TRIM(category_name)) = ?";
        $stmt = $conn->prepare($select_query);
        $stmt->bind_param("s", $category_lower);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('This category already exists.');</script>";
        } else {
            $stmt->close();
            $insert_query = "INSERT INTO category (category_name) VALUES (?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("s", $category);
            if ($stmt->execute()) {
                echo "<script>alert('Category added successfully.'); window.location.href=window.location.href;</script>";
            } else {
                echo "<script>alert('Error adding category.');</script>";
            }
        }
        $stmt->close();
    }
}

if (isset($_POST['edit_submit'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_category = trim($_POST['edit_categoryInput']);
    $edit_lower = strtolower($edit_category);

    if (empty($edit_category)) {
        echo "<script>alert('Category input cannot be empty.');</script>";
    } else {
        $check_query = "SELECT * FROM category WHERE LOWER(TRIM(category_name)) = ? AND category_id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("si", $edit_lower, $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('This category already exists.');</script>";
        } else {
            $stmt->close();
            $update_query = "UPDATE category SET category_name = ? WHERE category_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $edit_category, $edit_id);
            if ($stmt->execute()) {
                echo "<script>alert('Category updated successfully.'); window.location.href=window.location.href;</script>";
            } else {
                echo "<script>alert('Error updating category.');</script>";
            }
        }
        $stmt->close();
    }
}
?>

<div class="popup-category" id="categoryPopup">
    <div class="add-category-popup">
        <div class="add-category-header">
            <h1 class="add-category-title">Add Category</h1>
            <span class="close-btn" onclick="closeCategoryPopup()">&times;</span>
        </div>
        <div class="category-popup-body">
            <form method="post">
                <div class="add-category-field">
                    <input type="text" id="categoryInput" name="categoryInput" placeholder="Add Category" required
                        pattern="^[a-zA-Z\s&,]+$">
                </div>
                <div class="add-category-button">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="popup-category" id="editCategoryPopup">
    <div class="edit-category-popup">
        <div class="add-category-header">
            <h1 class="add-category-title">Edit Category</h1>
            <span class="close-btn" onclick="closeEditPopup()">&times;</span>
        </div>
        <div class="category-popup-body">
            <form method="post">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="add-category-field">
                    <input type="text" id="edit_categoryInput" name="edit_categoryInput" placeholder="Edit Category"
                        required pattern="^[a-zA-Z\s&,]+$">
                </div>
                <div class="add-category-button">
                    <button type="submit" name="edit_submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="category-container">
    <div class="category-main">
        <div class="category-header">
            <h1 class="category-title">Category</h1>
            <button type="button" class="category-popup-button" onclick="openCategoryPopup()">Add <i
                    class="fa fa-plus"></i></button>
        </div>

        <div class="category-body">
            <div class="category-data">
                <table>
                    <thead>
                        <tr>
                            <th class="thsno">S.No.</th>
                            <th class="thcategory">Category</th>
                            <th class="thaction" colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $select_category = "SELECT * FROM category";
                        $category_select = mysqli_query($conn, $select_category);
                        while ($row_data = mysqli_fetch_assoc($category_select)):
                            $i++;
                        ?>
                        <tr>
                            <td class="tdsno"><?php echo $i . '.'; ?></td>
                            <td class="tdcategory">
                                <?php echo htmlspecialchars($row_data['category_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="tdaction">
                                <button type="button"
                                    onclick="openEditPopup(<?php echo $row_data['category_id']; ?>,'<?php echo addslashes($row_data['category_name']); ?>')">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                            <td class="tdaction">
                                <button type="button" onclick="deleteCategory(<?php echo $row_data['category_id']; ?>)">
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
function openCategoryPopup() {
    document.getElementById("categoryPopup").style.display = "block";
}

function closeCategoryPopup() {
    document.getElementById("categoryPopup").style.display = "none";
}

function openEditPopup(id, name) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_categoryInput").value = name;
    document.getElementById("editCategoryPopup").style.display = "block";
}

function closeEditPopup() {
    document.getElementById("editCategoryPopup").style.display = "none";
}

function deleteCategory(id) {
    if (confirm("Are you sure you want to delete this category?")) {
        window.location.href = "Action/delete_category.php?id=" + id;
    }
}
</script>