<?php
session_start();

include_once("dbcon.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['delotp']) || empty($_POST['delotp'])) {
        echo "<script>alert('Invalid request. Missing email or OTP parameter.');</script>";
        header("Location:delete.php");
        exit;
    }

    // Verify OTP
    $inputOtp = $_POST['delotp'];
    $storedOtp = isset($_SESSION['delotp']) ? $_SESSION['delotp'] : null;

    if ($inputOtp == $storedOtp) {
        $email = $_POST['email'];

        $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            $con->begin_transaction();

            try {
                $stmt = $con->prepare("DELETE FROM userdata WHERE user_id = ?");
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->close();

                $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->close();

                // Commit the transaction
                $con->commit();

                unset($_SESSION['delotp']); // Clear OTP session after successful deletion
                echo "<script>alert('Account deleted successfully.');
                    window.location.href = 'index.php';
                    </script>";
            } catch (Exception $e) {
                // Rollback the transaction in case of an error
                $con->rollback();
                echo "<script>alert('Failed to delete account: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('User not found.');</script>";
        }

    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
else {
    echo "<script>alert('Invalid request method');</script>";
}
