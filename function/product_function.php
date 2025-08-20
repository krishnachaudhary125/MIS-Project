<?php
include '../Database/connection.php';

function product() {
    global $conn;

    // Handle Add to Cart (same code as before)...
    if (isset($_POST['add_to_cart'])) {
        $user_id = $_SESSION['user_id'] ?? 0;
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        if ($user_id == 0) {
            echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
            exit;
        }

        // Get or create cart
        $cart_query = "SELECT cart_id FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();

        if ($cart_result->num_rows > 0) {
            $cart = $cart_result->fetch_assoc();
            $cart_id = $cart['cart_id'];
        } else {
            $insert_cart = "INSERT INTO cart (user_id) VALUES (?)";
            $stmt = $conn->prepare($insert_cart);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_id = $stmt->insert_id;
        }

        // Check if product already exists in cart
        $check_item = "SELECT * FROM cart_item WHERE cart_id = ? AND product_id = ?";
        $stmt = $conn->prepare($check_item);
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $item_result = $stmt->get_result();

        if ($item_result->num_rows > 0) {
            $update_item = "UPDATE cart_item SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?";
            $stmt = $conn->prepare($update_item);
            $stmt->bind_param("iii", $quantity, $cart_id, $product_id);
            $stmt->execute();
        } else {
            $insert_item = "INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_item);
            $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
            $stmt->execute();
        }

        echo "<script>alert('Product added to cart!'); window.location.href='product.php';</script>";
        exit;
    }

    // Fetch all categories
    $category_query = "SELECT * FROM category ORDER BY category_id ASC";
    $categories = mysqli_query($conn, $category_query);

    if (!$categories) {
        echo "<p>Error fetching categories: " . mysqli_error($conn) . "</p>";
        return;
    }

    while ($cat = mysqli_fetch_assoc($categories)) {
        // Fetch products for this category
        $product_query = "SELECT * FROM product WHERE category_id = " . intval($cat['category_id']) . " ORDER BY product_id DESC";
        $products = mysqli_query($conn, $product_query);

        if (mysqli_num_rows($products) === 0) {
            continue; // ❌ Skip this category if no products
        }

        // ✅ Show only categories that have products
        echo "<h2 style='color:#fff; text-align:left; margin:25px 40px;'>" . htmlspecialchars($cat['category_name']) . "</h2>";
        echo '<div class="product-container" id="category-' . $cat['category_id'] . '">';

        $count = 0;
        while ($row = mysqli_fetch_assoc($products)) {
            $count++;
            $hiddenClass = ($count > 8) ? "hidden-product" : "";
?>
<div class="product-box <?php echo $hiddenClass; ?>">
    <div class="product-image">
        <img src="<?php echo '../uploads/' . htmlspecialchars($row['product_photo']); ?>"
            alt="<?php echo htmlspecialchars($row['product_name']); ?>"
            style="width:150px; height:150px; object-fit:cover; border-radius:10px;">
    </div>
    <div class="product-info" style="text-align:center; margin-top:15px;">
        <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
        <p style="height:60px; overflow:hidden; margin:5px 0;">
            <?php 
                $maxLength = 60;
                $description = htmlspecialchars($row['description']);
                echo (strlen($description) > $maxLength) ? substr($description, 0, $maxLength) . '...' : $description;
            ?>
        </p>
        <p>Price (Npr): <span><?php echo number_format($row['product_price'], 2); ?></span></p>

        <!-- Add to Cart Form -->
        <form method="post" action="" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <div style="display:flex; align-items:center; justify-content:center; gap:5px; margin-bottom:5px;">
                <button type="button" onclick="changeQuantity('qty-<?php echo $row['product_id']; ?>', -1)">-</button>
                <input type="number" name="quantity" id="qty-<?php echo $row['product_id']; ?>" value="1" min="1">
                <button type="button" onclick="changeQuantity('qty-<?php echo $row['product_id']; ?>', 1)">+</button>
            </div>
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </div>
</div>
<?php
        }
        echo '</div>';

        // Show "View More" button only if products > 8
        if ($count > 8) {
            echo '<div style="text-align:center; margin-bottom:30px;">
                    <button class="view-more-btn" onclick="showMore(' . $cat['category_id'] . ')">View More</button>
                  </div>';
        }
    }
}
?>

<script>
function changeQuantity(inputId, delta) {
    var input = document.getElementById(inputId);
    var current = parseInt(input.value);
    current += delta;
    if (current < 1) current = 1;
    input.value = current;
}

function showMore(categoryId) {
    let hiddenProducts = document.querySelectorAll("#category-" + categoryId + " .hidden-product");
    hiddenProducts.forEach(p => p.style.display = "block");
    event.target.style.display = "none"; // hide button after showing
}
</script>