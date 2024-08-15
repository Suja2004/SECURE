<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Change Password page -->
    <div class="change-password-page">
        <div class="return">
            <a href="index.php" class="arrow">&larr;</a>
            <h1>Change Password</h1>
        </div>
        <div class="change-password-page-content">
            <form id="changePasswordForm" class="change-password-form" method="post" action="verifyOtp.php">
                <input type="email" class="form-password-input" placeholder="Email" name="email" id="emailInput" required>
                <input type="password" class="form-password-input" placeholder="New Password" name="password" id="passwordInput" required>
                <div class="password-strength" id="passwordStrength"></div> 
                <div class="otp-page">
                    <input type="text" class="otp-form-input" name="otp" id="otpInput" placeholder="Enter OTP" required>
                    <button type="button" class="otp-send-btn" id="sendOtpBtn">Send OTP</button>
                    <button id="resendButton" class="btn" type="button" style="display:none;">Resend</button>
                </div>
                <input type="submit" class="form-password-btn" value="Confirm and Verify" id="submitBtn">
                <div class="loader" id="loader"></div> 
            </form>
        </div>
    </div>
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

document.addEventListener('DOMContentLoaded', function () {
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const resendButton = document.getElementById('resendButton');
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.getElementById('passwordInput');
    const submitBtn = document.getElementById('submitBtn');
    const passwordStrength = document.getElementById('passwordStrength');
    const loader = document.getElementById('loader');

    function sendOtp() {
        const email = emailInput.value;

        if (email) {
            loader.style.display = 'inline-block';
            sendOtpBtn.disabled = true;
            resendButton.disabled = true;

            fetch('sendOtp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showPopup('OTP sent to your email');
                        sendOtpBtn.style.display = 'none';
                        resendButton.style.display = 'block';
                    } else {
                        showPopup('Invalid Email');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showPopup('Error occurred while sending OTP');
                })
                .finally(() => {
                    loader.style.display = 'none';
                    sendOtpBtn.disabled = false;
                    resendButton.disabled = false;
                });
        } else {
            showPopup('Please enter your email');
        }
    }

    function validatePasswordStrength() {
        const password = passwordInput.value;

        const strongRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        const mediumRegex = /^(?=.*[a-zA-Z])(?=.*\d)[A-Za-z\d]{8,}$/;


        if (strongRegex.test(password)) {
            passwordStrength.textContent = 'Strong';
            passwordStrength.className = 'password-strength strong';
            submitBtn.disabled = false; // Enable submit button
            return true;
        } else if (mediumRegex.test(password)) {
            passwordStrength.textContent = 'Medium';
            passwordStrength.className = 'password-strength medium';
            submitBtn.disabled = true; // Disable submit button
            return true;
        }
        else {
            passwordStrength.textContent = 'Weak';
            passwordStrength.className = 'password-strength weak';
            submitBtn.disabled = true; // Disable submit button
            return false;
        }
    }

    submitBtn.addEventListener('click', function (event) {
        if (!validatePasswordStrength()) {
            event.preventDefault(); 
            showPopup('Password must be at least 8 characters long and contain letters, numbers, and special characters.');
        }
    });

    sendOtpBtn.addEventListener('click', sendOtp);
    resendButton.addEventListener('click', sendOtp);

    // Check password strength on input change
    passwordInput.addEventListener('input', validatePasswordStrength);
});

</script>
</html>
