<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECURE</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Login page -->
    <div class="login-page">
        <div class="login-page-content">
            <form class="login-page-form" action="login.php" method="POST">
                <input type="text" class="form-login-input" placeholder=" Email " name="email" required>
                <input type="password" class="form-login-input" placeholder="Password" name="password" required>
                <input type="submit" class="form-login-btn" value="Log In">
                <a href="changepassword.php" class="form-login-link">Forgot Password?</a>
                <span>or</span>
            </form>
            
            <button class="form-signup-btn signup" onclick="location.href='signup.php';">Sign Up</button>

        </div>
    </div>
    <!-- End of Login page -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <p id="popup-message"></p>
            <button class="btn" onclick="hidePopup()">Close</button>
        </div>
    </div>

</body>
<script>
    
function showPopup(message) {
    const Popup = document.getElementById("popup");
    const Message = document.getElementById("popup-message");

    Message.textContent = message;
    Popup.style.display = "flex";
}

function hidePopup() {
    document.getElementById("popup").style.display = "none";
}
</script>

</html>