<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login page
    exit();
}
$user_email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="delete-account-page">
        <div class="return">
            <a href="profile.php" class="arrow">&larr;</a>
            <h1>Delete Account</h1>
        </div>
        <div class="delete-account-page-content">
            <form id="deleteAccountForm" class="delete-account-form" method="post" action="deleteaccount.php">
                <input type="email" class="form-delete-input" placeholder="Email" name="email" id="emailInput"
                value="<?php echo htmlspecialchars($user_email); ?>"  required>
                <div class="otp-page">
                    <input type="text" class="otp-form-input" name="delotp" id="otpInput" placeholder="Enter OTP" required >
                    <button type="button" class="otp-send-btn" id="sendOtpBtn">Send OTP</button>
                    <button id="resendButton" class="btn" type="button" style="display:none;">Resend</button>
                </div>
                <input type="submit" class="form-delete-btn btn" id="deleteAccountBtn" value="Delete Account">
                <div class="loader" id="loader"></div>
            </form>
        </div>
    </div>
</body>
<script>
    
document.addEventListener('DOMContentLoaded', function () {
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const resendButton = document.getElementById('resendButton');
    const emailInput = document.getElementById('emailInput');
    const submitBtn = document.getElementById('submitBtn');
    const passwordStrength = document.getElementById('passwordStrength');
    const loader = document.getElementById('loader');
 
    
    function sendOtp() {
        const email = emailInput.value;

        if (email) {
            loader.style.display = 'inline-block';
            sendOtpBtn.disabled = true;
            resendButton.disabled = true;

            fetch('deleteotp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('OTP sent to your email');
                        sendOtpBtn.style.display = 'none';
                        resendButton.style.display = 'block';
                    } else {
                        alert('Invalid Email');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error occurred while sending OTP');
                })
                .finally(() => {
                    loader.style.display = 'none';
                    sendOtpBtn.disabled = false;
                    resendButton.disabled = false;
                });
        } else {
            alert('Please enter your email');
        }
    }
    sendOtpBtn.addEventListener('click', sendOtp);
    resendButton.addEventListener('click', sendOtp);

});
</script>
</html>