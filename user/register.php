<?php
include "header.php";
?>

<div class="register-container">
    <form action="" method="post">
        <h1>Create Account</h1>
        <div class="register-form">
            <div class="register-field">
                <input type="text" name="fname" value="" placeholder="Full Name">
            </div>
            <div class="register-field">
                <input type="text" name="phone" value="" placeholder="Mobile Number">
            </div>
            <div class="register-field">
                <input type="email" name="email" value="" placeholder="E-Mail">
            </div>
            <div class="register-field">
                <input type="password" name="psw" value="" placeholder="Password">
            </div>
            <div class="register-field">
                <input type="password" name="cpsw" value="" placeholder="Confirm Password">
            </div>
            <div class="register-field-checkbox">
                <input type="checkbox" id="togglePassword">
                <label for="togglePassword" id="toggleLabel">&nbsp;&nbsp;Show Password</label>
            </div>
            <button type="submit">Register</button>
            <div class="register-a">
                <ul>
                    <li>
                        <a href="login.php">Already have an account?</a>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>

<script>
const checkbox = document.getElementById("togglePassword");
const password = document.querySelector('input[name="psw"]');
const confirmPassword = document.querySelector('input[name="cpsw"]');
const label = document.getElementById("toggleLabel");

checkbox.addEventListener("change", function() {
    const type = this.checked ? "text" : "password";
    password.type = type;
    confirmPassword.type = type;
    label.innerHTML = this.checked ? "&nbsp;&nbsp;Hide Password" : "&nbsp;&nbsp;Show Password";
});
</script>