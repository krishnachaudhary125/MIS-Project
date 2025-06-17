<?php
include "header.php";
include "../database/connection.php";

$error = "";
$loginSuccess = false;
$redirectUrl = "";

if (isset($_POST['submit']) == true) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pswHash = sha1($password);

    $select_query = "SELECT user_id AS id, fullname, role FROM users WHERE email='$email' && password='$pswHash'
    UNION
    SELECT admin_id AS id, fullname, role FROM admin WHERE email='$email' && password='$pswHash'";
    
    $result = mysqli_query($conn, $select_query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $loginSuccess = true;

        if ($row['role'] == 'admin' || $row['role'] == 'owner') {
            $_SESSION['role'] = $row['role'];
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['fullname'];
            $redirectUrl = "../admin/dashboard.php?statistics";
        } elseif ($row['role'] == 'user') {
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'];
            $redirectUrl = "index.php";
        }
    } else {
        $error = 'Incorrect email or password!';
    }
}
?>


<div class="login-container">
    <form action="#" method="post">
        <h1>Log In To Wonder Kitchen</h1>
        <div class="login-form">
            <div id="errorDisplay" style="color: red; margin-bottom: 10px;">
                <?php if (!empty($error)): ?>
                <?php echo $error; ?>
                <?php endif; ?>
            </div>
            <div class="login-field">
                <input type="email" value="" id="email" name="email" placeholder="E-Mail">
            </div>
            <div class="login-field">
                <input type="password" value="" id="password" name="password" placeholder="Password">
            </div>
            <div class="login-field-checkbox">
                <input type="checkbox" id="togglePassword">
                <label for="togglePassword" id="toggleLabel">&nbsp;&nbsp;Show Password</label>
            </div>
            <button type="submit" id="submit" name="submit">Log In</button>
            <div class="login-a">
                <ul>
                    <li>
                        <a href="register.php">Create New Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>

<?php if ($loginSuccess): ?>
<div id="loginSuccessPopup" class="login-success-popup">
    <div class="login-popup">
        <div class="login-popup-field">
            <h2>Login Successful!</h2>
        </div>
        <div class="login-popup-field">
            <p>Redirecting...</p>
        </div>
    </div>
</div>
<script>
setTimeout(function() {
    window.location.href = "<?php echo $redirectUrl; ?>";
}, 500);
</script>
<?php endif; ?>


<script>
const checkbox = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");
const label = document.getElementById("toggleLabel");

checkbox.addEventListener("change", function() {
    const isChecked = checkbox.checked;
    passwordInput.type = isChecked ? "text" : "password";
    label.innerHTML = isChecked ? "&nbsp;&nbsp;Hide Password" : "&nbsp;&nbsp;Show Password";
});
</script>