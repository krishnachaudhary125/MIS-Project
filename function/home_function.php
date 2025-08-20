<?php
function home(){ 
    ?>
<div style="text-align:center; padding:40px;">
    <h1 style="color:aquamarine; font-size:42px;">Welcome to Wonder Kitchen</h1>
    <p style="font-size:18px; color:aquamarine;">
        Your one-stop shop for Kitchen Utensils, Cookware, and Bakeware.
    </p>

    <div style="margin-top:30px;">
        <a href="product.php"
            style="padding:12px 25px; background:#333; color:#fff; text-decoration:none; border-radius:5px;">
            Explore Products
        </a>
    </div>

    <div style="margin-top:50px; display:flex; justify-content:center; gap:20px; flex-wrap:wrap;">
        <div style="width:250px; padding:20px; background:#f8f8f8; border-radius:10px;">
            <h3>🍳 Cookware</h3>
            <p>Find pans, pots, and skillets for daily cooking.</p>
        </div>
        <div style="width:250px; padding:20px; background:#f8f8f8; border-radius:10px;">
            <h3>🥗 Utensils</h3>
            <p>Spoons, spatulas, ladles, and more.</p>
        </div>
        <div style="width:250px; padding:20px; background:#f8f8f8; border-radius:10px;">
            <h3>🍰 Bakeware</h3>
            <p>Cake pans, muffin trays, and baking tools.</p>
        </div>
    </div>
</div>
<?php
}
?>