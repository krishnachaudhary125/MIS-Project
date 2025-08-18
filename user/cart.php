<?php
include "header.php";
include "../Database/connection.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    echo '<h2 style="color: red; text-align: center; padding: 155px 0;">
            Please Sign In to see your cart data. 
          <a href="login.php" style="color: blue;">Click Here</a>
          </h2>';
    include "footer.php";
    exit;
}

// Remove items from cart
if (isset($_POST['removefromcart'])) {
    if (!empty($_POST['removeproduct'])) {
        foreach ($_POST['removeproduct'] as $remove_item) {
            $remove_item = intval($remove_item);
            $delete_query = "DELETE FROM cart_item WHERE cart_item_id = $remove_item";
            mysqli_query($conn, $delete_query);
        }
        echo "<script>window.open('cart.php', '_self');</script>";
    } else {
        echo "<script>alert('Please select a product to remove.');</script>";
    }
}
?>

<link rel="stylesheet" href="cart.css">

<div class="products-main">
    <div class="products-header">
        <h1 class="products-title">🛒 Your Cart</h1>
    </div>

    <div class="product-cart-body">
        <div class="product-data">
            <form action="" method="post">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th class="cart-th">S.No.</th>
                            <th class="cart-th">Product Photo</th>
                            <th class="cart-th">Product Name</th>
                            <th class="cart-th">Product Price (NPR)</th>
                            <th class="cart-th">Quantity</th>
                            <th class="cart-th">Total Price (NPR)</th>
                            <th class="cart-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $total_price = 0;

                        $query = "SELECT ci.cart_item_id, p.product_id, p.product_photo, p.product_name, p.product_price, ci.quantity
                                  FROM cart_item ci
                                  JOIN product p ON ci.product_id = p.product_id
                                  JOIN cart c ON ci.cart_id = c.cart_id
                                  WHERE c.user_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0):
                            $sno = 0;
                            while ($row = $result->fetch_assoc()):
                                $sno++;
                                $item_total = $row['product_price'] * $row['quantity'];
                                $total_price += $item_total;
                        ?>
                        <tr>
                            <td class="cart-td"><?php echo $sno; ?></td>
                            <td class="cart-td"><img src="<?php echo '../uploads/' . $row['product_photo']; ?>"
                                    alt="Product Photo" class="products-cart-photo"></td>
                            <td class="cart-td"><?php echo $row['product_name']; ?></td>
                            <td class="cart-td"><?php echo number_format($row['product_price'],2); ?></td>
                            <td class="cart-td"><?php echo $row['quantity']; ?></td>
                            <td class="cart-td"><?php echo number_format($item_total,2); ?></td>
                            <td class="cart-td">
                                <input type="checkbox" name="removeproduct[]"
                                    value="<?php echo $row['cart_item_id']; ?>">
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                            echo "<tr><td colspan='7' class='empty-cart'>Your cart is empty.</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>

                <div class="checkout">
                    <p>Total Price: NPR <?php echo number_format($total_price,2); ?></p>
                    <button type="submit" class="remove" name="removefromcart">Remove Selected</button>
                </div>
            </form>

            <!-- Checkout Form -->
            <form id="checkoutForm" action="./checkout.php" method="post">
                <?php
                if ($result->num_rows > 0):
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()):
                ?>
                <input type="hidden" name="cart_item_id[]" value="<?php echo $row['cart_item_id']; ?>">
                <input type="hidden" name="quantity[]" value="<?php echo $row['quantity']; ?>">
                <?php endwhile; endif; ?>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                <input type="submit" class="check-out" value="Check Out" onclick="return checkCart();">
            </form>
        </div>
    </div>
</div>

<script>
function checkCart() {
    var totalPrice = <?php echo $total_price; ?>;
    if (totalPrice === 0) {
        alert("Your cart is empty. Please add products before checking out.");
        return false;
    }
    return true;
}
</script>