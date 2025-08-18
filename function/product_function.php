<?php
include '../Database/connection.php';

function product() {
    global $conn; // Use the database connection

    // Fetch products from the database
    $query = "SELECT * FROM product ORDER BY product_id DESC"; // adjust table name
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
                $maxLength = 60; // max characters to display
                $description = htmlspecialchars($row['description']);
                if (strlen($description) > $maxLength) {
                    echo substr($description, 0, $maxLength) . '...';
                } else {
                    echo $description;
                }
            ?>
        </p>
        <p>Price: $<?php echo number_format($row['product_price'], 2); ?></p>
        <button>Add to Cart</button>
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