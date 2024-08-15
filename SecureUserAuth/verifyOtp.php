<?php
session_start(); // Start session if not already started
include_once("dbcon.php"); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['otp']) || empty($_POST['otp'])) {
        echo "<script>alert('Invalid request. Missing email or OTP parameter.');</script>";
        header("Location:changepassword.php");
        exit;
    }

    // Verify OTP
    $inputOtp = $_POST['otp'];
    $storedOtp = isset($_SESSION['otp']) ? $_SESSION['otp'] : null;

    if ($inputOtp == $storedOtp) {

        // Clear OTP from session after successful verification
        unset($_SESSION['otp']);

        $email = $_POST['email'];
        $newPassword = $_POST['password'];

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE email = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $email);

        // Execute the update statement
        if ($stmt->execute()) {
            echo "<script>alert('Password updated successfully.');</script>";
            echo "<script>window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Failed to update password. Please try again later.');</script>";
            
        }


        // Close statement and connection
        $stmt->close();
        $con->close();
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
        header("Location:changepassword.php");
    }
} else {
    echo "<script>alert('Invalid request method.');</script>";
}
?>
