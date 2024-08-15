<?php
session_start();
include_once 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp']);
    $current_time = time();

    if (!isset($_SESSION['temp_user'])) {
        echo "<script>alert('Session expired. Please register again.');</script>";
        echo "<script>window.location.href = 'signup.php'; </script>";
        exit();
    }

    $temp_user = $_SESSION['temp_user'];
    $stored_otp = $temp_user['otp'];
    $otp_time = $temp_user['otp_time'];
    $otp_validity_duration = 5 * 60; // OTP validity duration in seconds (5 minutes)

    if (($current_time - $otp_time) >= $otp_validity_duration) {
        unset($_SESSION['temp_user']);
        echo "<script>alert('OTP has expired. Please register again.');</script>";
        echo "<script>window.location.href = 'signup.php'; </script>";
        exit();
    }

    if ($entered_otp == $stored_otp) {
        if ($stmt = $con->prepare("INSERT INTO users (username, email, password, is_active) VALUES (?, ?, ?, ?)")) {
            $is_active = 1; 

            $stmt->bind_param('sssi', $temp_user['username'], $temp_user['email'], $temp_user['password'], $is_active);

            if ($stmt->execute()) {
                unset($_SESSION['temp_user']);
                echo "<script>alert('User registered successfully.');</script>";
                echo "<script>window.location.href = 'index.php'; </script>";
                exit();
            } else {
                echo "<script>alert('Failed to register user: " . $stmt->error . "');</script>";
                echo "<script>window.location.href = 'signup.php'; </script>";
                exit();
            }
        } else {
            echo "<script>alert('Failed to prepare the SQL statement.');</script>";
            echo "<script>window.location.href = 'signup.php'; </script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
        echo "<script>window.location.href = 'signupotp.php'; </script>";
        exit();
    }
}
$con->close();
