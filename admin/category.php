<?php
include "../database/connection.php";

// Handle Add Category
if (isset($_POST['submit'])) {
    $category = trim($_POST['categoryInput']);
    $category_lower = strtolower($category);

    if (empty($category)) {
        echo "<script>alert('Category input cannot be empty.');</script>";
    } else {
        $select_query = "SELECT * FROM category WHERE LOWER(TRIM(category_name)) = ?";
        $stmt = $conn->prepare($select_query);

        if ($stmt === false) {
            error_log("SQL Prepare Error: " . $conn->error);
            echo "<script>alert('A database error occurred. Please try again later.');</script>";
            exit();
        }

        $stmt->bind_param("s", $category_lower);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('This category already exists.');</script>";
        } else {
            $stmt->close();

            $sqlQuery = "INSERT INTO category (category_name) VALUES (?)";
            $stmt = $conn->prepare($sqlQuery);

            if ($stmt === false) {
                error_log("SQL Prepare Error: " . $conn->error);
                echo "<script>alert('A database error occurred. Please try again later.');</script>";
                exit();
            }

            $stmt->bind_param("s", $category);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Category added successfully.');
                    window.location.href = window.location.href;
                </script>";
            } else {
                echo "<script>alert('Error adding category.');</script>";
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
            <form action="" method="post" name="category_popup">
                <div class="add-category-field">
                    <input type="text" id="categoryInput" name="categoryInput" placeholder="Add Category" required
                        pattern="^[a-zA-Z0-9\s]+$">
                </div>
                <div class="add-category-button">
                    <button type="submit" name="submit">Submit</button>
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
                                <button type="button" onclick="editCategory(<?php echo $row_data['category_id']; ?>)">
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

function deleteCategory(id) {
    if (confirm("Are you sure you want to delete this category?")) {
        window.location.href = "Action/delete_category.php?id=" + id;
    }
}

function editCategory(id) {
    alert("Edit functionality is not implemented yet for category ID: " + id);
}
</script>