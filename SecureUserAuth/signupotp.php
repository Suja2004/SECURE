<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <div class="signup-otp-page">
        <div class="signup-otp-page-content">
            <h2>VERIFY OTP</h2>
            <form class="signup-otp-page-form" action="signupotpverify.php" method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <button class="signup-otp-send-btn" type="submit">Verify OTP</button>
            </form>
        </div>
    </div>
</body>
</html>