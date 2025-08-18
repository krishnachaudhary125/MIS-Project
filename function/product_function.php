<?php
include '../Database/connection.php';

function product() {
    global $conn; // Use the database connection

    // Handle Add to Cart
    if (isset($_POST['add_to_cart'])) {
        $user_id = $_SESSION['user_id'] ?? 0;
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        if ($user_id == 0) {
            echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
            exit;
        }

        // Check if user has a cart
        $cart_query = "SELECT cart_id FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();

        if ($cart_result->num_rows > 0) {
            $cart = $cart_result->fetch_assoc();
            $cart_id = $cart['cart_id'];
        } else {
            // Create new cart
            $insert_cart = "INSERT INTO cart (user_id) VALUES (?)";
            $stmt = $conn->prepare($insert_cart);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_id = $stmt->insert_id;
        }

        // Check if product already in cart
        $check_item = "SELECT * FROM cart_item WHERE cart_id = ? AND product_id = ?";
        $stmt = $conn->prepare($check_item);
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $item_result = $stmt->get_result();

        if ($item_result->num_rows > 0) {
            // Update quantity
            $update_item = "UPDATE cart_item SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?";
            $stmt = $conn->prepare($update_item);
            $stmt->bind_param("iii", $quantity, $cart_id, $product_id);
            $stmt->execute();
        } else {
            // Insert new cart item
            $insert_item = "INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_item);
            $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
            $stmt->execute();
        }

        echo "<script>alert('Product added to cart!'); window.location.href='product.php';</script>";
        exit;
    }

    // Fetch products from the database
    $query = "SELECT * FROM product ORDER BY product_id DESC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p>Error fetching products: " . mysqli_error($conn) . "</p>";
        return;
    }

    echo '<div class="product-container">';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
<div class="product-box"
    style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; width: 300px; min-height: 400px; padding: 20px; border-radius: 20px; background-color: rgba(0,0,0,0.85); color: #ffffffdc; box-sizing: border-box;">
    <div class="product-image">
        <img src="<?php echo '../uploads/' . htmlspecialchars($row['product_photo']); ?>"
            alt="<?php echo htmlspecialchars($row['product_name']); ?>"
            style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
    </div>
    <div class="product-info" style="text-align: center; margin-top: 15px;">
        <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
        <p style="height: 60px; overflow: hidden; margin: 5px 0;">
            <?php 
                $maxLength = 60;
                $description = htmlspecialchars($row['description']);
                echo (strlen($description) > $maxLength) ? substr($description, 0, $maxLength) . '...' : $description;
            ?>
        </p>
        <p>Price (Npr): <span
                id="price-<?php echo $row['product_id']; ?>"><?php echo number_format($row['product_price'], 2); ?></span>
        </p>

        <!-- Add to Cart Form with quantity + button -->
        <form method="post" action="" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <div style="display:flex; align-items:center; justify-content:center; gap:5px; margin-bottom:5px;">
                <button type="button" onclick="changeQuantity('qty-<?php echo $row['product_id']; ?>', -1)"
                    style="width:30px; height:30px; font-size:20px; border-radius:5px; background-color:#000000cc; color:#ffffff; border:none; cursor:pointer;">-</button>
                <input type="number" name="quantity" id="qty-<?php echo $row['product_id']; ?>" value="1" min="1"
                    style="width:50px; text-align:center; font-size:16px; border-radius:5px; border:1px solid #ffffffcc; background-color: rgba(0,0,0,0.3); color:#fff;">
                <button type="button" onclick="changeQuantity('qty-<?php echo $row['product_id']; ?>', 1)"
                    style="width:30px; height:30px; font-size:20px; border-radius:5px; background-color:#000000cc; color:#ffffff; border:none; cursor:pointer;">+</button>
            </div>
            <button type="submit" name="add_to_cart"
                style="padding:7px 15px; border-radius:25px; border:2px solid #ffffffdc; background-color:transparent; color:#ffffffdc; cursor:pointer;">Add
                to Cart</button>
        </form>
    </div>
</div>

<?php
        }
    } else {
        echo "<p>No products available.</p>";
    }

    echo '</div>';
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
</script>