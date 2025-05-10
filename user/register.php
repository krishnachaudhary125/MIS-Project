<?php
include "../database/connection.php";
include "header.php";

$error = "";
$registerSuccess = false;

if(isset($_POST['submit'])){
    $fname = $_POST['fname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $psw = $_POST['psw'];
    $cpsw = $_POST['cpsw'];
    
    // Server-side validation
    if (empty($fname) || empty($phone) || empty($email) || empty($psw) || empty($cpsw)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[A-Z][a-zA-Z ]*$/', $fname)) {
        $error = "Full name must start with a capital letter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $psw)) {
        $error = "Strong password is required.";
    } elseif ($psw !== $cpsw) {
        $error = "Passwords do not match.";
    } else {
        $hashPsw = sha1($psw);

        $select_query = "SELECT * FROM users WHERE email = '$email' UNION SELECT * FROM admin WHERE email = '$email'";
        $res_select = mysqli_query($conn, $select_query);
        $check = mysqli_num_rows($res_select);
        if($check > 0){
            $error = "Email already exists.";
        } else {
            $insert_query = "INSERT INTO users (fullname, phone, email, password) VALUES ('$fname', '$phone', '$email', '$hashPsw')";
            $result = mysqli_query($conn, $insert_query);
            if($result) {
                $registerSuccess = true;
            }
        }
    }
}
?>

<div class="register-container">
    <form id="registerForm" action="" method="post">
        <h1>Create Account</h1>
        <div class="register-form">
            <div id="errorDisplay" style="color: red; margin-bottom: 10px;">
                <?php if (!empty($error)): ?>
                <?php echo $error; ?>
                <?php endif; ?>
            </div>
            <div class="register-field">
                <input type="text" name="fname" id="fname"
                    value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>"
                    placeholder="Full Name">
            </div>
            <div class="register-field">
                <input type="text" name="phone" id="phone"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                    placeholder="Mobile Number">
            </div>
            <div class="register-field">
                <input type="email" name="email" id="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    placeholder="E-Mail">
            </div>
            <div class="register-field">
                <input type="password" name="psw" id="psw" value="" placeholder="Password">
            </div>
            <div class="register-field">
                <input type="password" name="cpsw" id="cpsw" value="" placeholder="Confirm Password">
            </div>
            <div class="register-field-checkbox">
                <input type="checkbox" id="togglePassword">
                <label for="togglePassword" id="toggleLabel">&nbsp;&nbsp;Show Password</label>
            </div>
            <button type="submit" name="submit">Register</button>
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

<?php if ($registerSuccess): ?>
<div id="registerSuccessPopup" class="register-success-popup">
    <div class="register-popup">
        <div class="register-popup-field">
            <h2>Registration Successful!</h2>
        </div>
        <div class="register-popup-field">
            <p>Your account has been created.</p>
        </div>
        <button onclick="closePopup()">OK</button>
    </div>
</div>
<?php endif; ?>

<script>
const checkbox = document.getElementById("togglePassword");
const password = document.getElementById("psw");
const confirmPassword = document.getElementById("cpsw");
const label = document.getElementById("toggleLabel");
const form = document.getElementById("registerForm");
const errorDisplay = document.getElementById("errorDisplay");

// Regex patterns
const nameRegex = /^[A-Z][a-zA-Z ]*$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
const phoneRegex = /^[\d\s\-()+]{10,}$/; // Basic phone number validation

checkbox.addEventListener("change", function() {
    const type = this.checked ? "text" : "password";
    password.type = type;
    confirmPassword.type = type;
    label.innerHTML = this.checked ? "&nbsp;&nbsp;Hide Password" : "&nbsp;&nbsp;Show Password";
});

form.addEventListener('submit', function(e) {
    // Reset all borders and clear previous error
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.style.border = '';
    });
    errorDisplay.textContent = '';

    let errorMessage = '';
    let hasErrors = false;

    // Get field values
    const fname = document.getElementById('fname').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const psw = document.getElementById('psw').value.trim();
    const cpsw = document.getElementById('cpsw').value.trim();

    // Check for empty fields
    if (!fname || !phone || !email || !psw || !cpsw) {
        errorMessage = 'All fields are required.';
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.style.border = '2px solid red';
            }
        });
        hasErrors = true;
    }

    // Validate full name
    else if (!nameRegex.test(fname)) {
        document.getElementById('fname').style.border = '2px solid red';
        errorMessage = 'Full name must start with a capital letter.';
        hasErrors = true;
    }

    // Validate email
    else if (!emailRegex.test(email)) {
        document.getElementById('email').style.border = '2px solid red';
        errorMessage = 'Please enter a valid email address.';
        hasErrors = true;
    }

    // Validate phone
    else if (!phoneRegex.test(phone)) {
        document.getElementById('phone').style.border = '2px solid red';
        errorMessage = 'Please enter a valid phone number.';
        hasErrors = true;
    }

    // Validate password strength
    else if (!passwordRegex.test(psw)) {
        document.getElementById('psw').style.border = '2px solid red';
        errorMessage =
            'Strong password is required.';
        hasErrors = true;
    }

    // Check password match
    else if (psw !== cpsw) {
        document.getElementById('psw').style.border = '2px solid red';
        document.getElementById('cpsw').style.border = '2px solid red';
        errorMessage = 'Passwords do not match.';
        hasErrors = true;
    }

    if (hasErrors) {
        e.preventDefault();
        errorDisplay.textContent = errorMessage;
    }
});

// Clear red border when user starts typing
const inputs = form.querySelectorAll('input');
inputs.forEach(input => {
    input.addEventListener('input', function() {
        if (this.value.trim()) {
            this.style.border = '';
        }
    });
});

function closePopup() {
    document.getElementById('registerSuccessPopup').style.display = 'none';
    window.location.href = 'login.php';
}
</script>