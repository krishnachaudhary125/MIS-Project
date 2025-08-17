<?php
include '../Database/connection.php';

if (isset($_POST['submit'])) {

    $productName = trim($_POST['product_name']);
    $categoryId = $_POST['category_id'];
    $description = trim($_POST['description']);
    $productPrice = trim($_POST['product_price']);
    $productKeyword = trim($_POST['product_keyword']);

    $productPhoto = $_FILES['product_photo']['name'];
    $productPhotoTemp = $_FILES['product_photo']['tmp_name'];
    $photoPath = "../Database/uploads/photos/" . time() . basename($productPhoto);

    move_uploaded_file($productPhotoTemp, $photoPath);

    // Check duplicate product
    $checkQuery = "SELECT * FROM product WHERE product_name = ? AND category_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $productName, $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('This product is already added. Try adding another.');</script>";
    } else {
        $stmt->close();

        $insertQuery = "INSERT INTO product 
    (product_name, category_id, description, product_price, product_keyword, product_photo) 
    VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sisdss", $productName, $categoryId, $description, $productPrice, $productKeyword, $photoPath);


        if ($stmt->execute()) {
            echo "<script>alert('New product added successfully.'); window.location.href=window.location.href;</script>";
        } else {
            echo "<script>alert('Error adding product.');</script>";
        }
        $stmt->close();
    }
}
?>

<!-- Add Product Popup -->
<div class="popup-overlay" id="addProductPopup">
    <div class="popup-box">
        <div class="popup-header">
            <h2>Add Product</h2>
            <span class="close-btn" onclick="closeProductPopup()">&times;</span>
        </div>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()" class="popup-form">
            <div class="custom-select-wrapper">
                <select name="category_id" id="category_id" required>
                    <option value="" disabled selected hidden>Select Category</option>
                    <?php
        $select_category = "SELECT * FROM category";
        $category_select = mysqli_query($conn, $select_category);
        while ($row = mysqli_fetch_assoc($category_select)):
        ?>
                    <option value="<?= $row['category_id']; ?>"><?= htmlspecialchars($row['category_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-field">
                <input type="text" id="product_name" name="product_name" placeholder="Product Name" required>
            </div>
            <div class="form-field">
                <textarea id="description" name="description" placeholder="Description" required></textarea>
            </div>
            <div class="form-field">
                <input type="text" id="product_price" name="product_price" placeholder="Price" required>
            </div>
            <div class="form-field">
                <input type="text" id="product_keyword" name="product_keyword" placeholder="Keyword" required>
            </div>
            <div class="form-field">
                <label for="product_photo">Photo</label>
                <input type="file" id="product_photo" name="product_photo" accept="image/*" required>
            </div>
            <div class="form-button">
                <button type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>
</div>



<!-- Product Table -->
<div class="category-container">
    <div class="category-main">
        <div class="category-header">
            <h1 class="category-title">Products</h1>
            <button type="button" class="category-popup-button" onclick="openProductPopup()">Add Product</button>
        </div>
        <div class="category-data">
            <table>
                <thead>
                    <tr>
                        <th class="thsno">S.No.</th>
                        <th class="tdcategory">Photo</th>
                        <th class="tdcategory">Product Name</th>
                        <th class="tdcategory">Category</th>
                        <th class="tdcategory">Description</th>
                        <th class="tdcategory">Price</th>
                        <th class="tdcategory">Keyword</th>
                        <th class="thadmin">Admin ID</th>
                        <th class="thaction">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $select_products = "SELECT p.*, c.category_name FROM product p INNER JOIN category c ON p.category_id = c.category_id";
                    $result = mysqli_query($conn, $select_products);
                    while ($row = mysqli_fetch_assoc($result)):
                        $i++;
                    ?>
                    <tr>
                        <td class="tdsno"><?= $i ?>.</td>
                        <td class="tdcategory">
                            <?php if (!empty($row['product_photo'])): ?>
                            <img src="<?= $row['product_photo']; ?>" class="product_photo" alt="Product Photo">
                            <?php endif; ?>
                        </td>
                        <td class="tdcategory"><?= htmlspecialchars($row['product_name']); ?></td>
                        <td class="tdcategory"><?= htmlspecialchars($row['category_name']); ?></td>
                        <td class="tdcategory"><?= htmlspecialchars($row['description']); ?></td>
                        <td class="tdcategory"><?= htmlspecialchars($row['product_price']); ?></td>
                        <td class="tdcategory"><?= htmlspecialchars($row['product_keyword']); ?></td>
                        <td class="adminid"><?= $row['admin_id']; ?></td>
                        <td class="tdaction">
                            <button type="button" onclick="deleteProduct(<?= $row['product_id']; ?>)">
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

<script>
function openProductPopup() {
    document.getElementById("addProductPopup").style.display = "block";
}

function closeProductPopup() {
    document.getElementById("addProductPopup").style.display = "none";
}

function deleteProduct(id) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = "./Action/product_delete.php?id=" + id;
    }
}

function validateForm() {
    let isValid = true;
    const fields = ['product_name', 'description', 'category_id', 'product_price', 'product_keyword', 'product_photo'];
    fields.forEach(field => {
        const el = document.getElementById(field);
        if (!el.value || el.value.trim() === '') {
            alert('Please fill all required fields.');
            isValid = false;
        }
    });
    return isValid;
}

function openProductPopup() {
    document.getElementById("addProductPopup").style.display = "flex";
}

function closeProductPopup() {
    document.getElementById("addProductPopup").style.display = "none";
}
</script>


<?php include 'footer.php'; ?>