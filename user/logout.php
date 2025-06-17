<?php
include "header.php";
session_start();
session_unset();
session_destroy();

$redirectUrl = "index.php";
$logoutSuccess = true;
?>
<?php if ($logoutSuccess): ?>

<div id="logoutSuccessPopup" class="logout-success-popup">
    <div class="logout-popup">
        <div class="logout-popup-field">
            <h2>Logout Successful!</h2>
        </div>
        <div class="logout-popup-field">
            <p>Redirecting.....to home.</p>
        </div>
    </div>
</div>
<script>
setTimeout(function() {
    window.location.href = "<?php echo $redirectUrl; ?>";
}, 500);
</script>
<?php endif; ?>