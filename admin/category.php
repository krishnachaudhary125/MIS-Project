<?php
include "../database/connection.php";
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
                    <label for="categoryInput">Add Category</label>
                    <input type="text" id="categoryInput" name="categoryInput" placeholder="Input Category" required
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
                                <button type="submit"><i class="fa fa-edit"></i></button>
                            </td>
                            <td class="tdaction">
                                <button type="submit"><i class="fa fa-trash"></i></button>
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
};

function closeCategoryPopup() {
    document.getElementById("categoryPopup").style.display = "none";
};
</script>