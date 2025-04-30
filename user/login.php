<?php
include "header.php";
?>

<div class="login-container">
    <form action="">
        <h1>Log In To Wonder Kitchen</h1>
        <div class="login-form">
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
            <button type="submit">Log In</button>
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