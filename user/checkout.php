<?php
include "header.php";
include "../Database/connection.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    echo '<h2 style="color: red; text-align: center; padding: 155px 0;">
            Please Sign In to proceed with checkout. 
          <a href="login.php" style="color: blue;">Click Here</a>
          </h2>';
    include "footer.php";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['cart_item_id']) && isset($_POST['quantity']) && isset($_POST['total_price'])){
        $cart_item_ids = $_POST['cart_item_id'];
        $quantities = $_POST['quantity'];
        $user_id = $_POST['user_id'];
        $total_amount = $_POST['total_price'];

        $cart_item_ids_str = implode(",", $cart_item_ids);

        // Generate transaction UUID
        $transaction_uuid = mt_rand(100000, 999999);

        // Generate signature
        $message = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=EPAYTEST";
        $secret_key = '8gBm/:&EnhH.1/q'; // replace with your secret key
        $s = hash_hmac('sha256', $message, $secret_key, true);
        $signature = base64_encode($s);

        // Save in session
        $_SESSION['signature'] = $signature;
        $_SESSION['cart_item_ids_str'] = $cart_item_ids_str;
        $_SESSION['total_amount'] = $total_amount;
        $_SESSION['transaction_uuid'] = $transaction_uuid;
        $_SESSION['quantities'] = $quantities;
    } else {
        echo "<script>alert('Invalid checkout request.'); window.location.href='cart.php';</script>";
        exit;
    }
}
?>

<link rel="stylesheet" href="cart.css">

<div class="products-main">
    <div class="products-header">
        <h1 class="products-title">💳 Checkout</h1>
    </div>

    <div class="payment-option">
        <div class="available">
            <div class="available-system">
                <h2 class="payment-header">Select Payment System</h2>
            </div>
            <div class="payment">
                <!-- eSewa Payment Form -->
                <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                    <input type="hidden" name="amount" value="<?php echo $total_amount; ?>">
                    <input type="hidden" name="tax_amount" value="0">
                    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                    <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
                    <input type="hidden" name="product_code" value="EPAYTEST">
                    <input type="hidden" name="product_service_charge" value="0">
                    <input type="hidden" name="product_delivery_charge" value="0">
                    <input type="hidden" name="success_url" value="https://localhost/MIS-Project/user/success.php">
                    <input type="hidden" name="failure_url" value="https://localhost/MIS-Project/user/failure.php">
                    <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
                    <input type="hidden" name="signature" value="<?php echo $signature; ?>">
                    <input type="image" src="../photos/esewa.png" name="esewa" alt="Pay with eSewa" style="width: 50%;">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>