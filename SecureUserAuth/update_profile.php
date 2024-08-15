<?php
// Include the database connection file
include_once 'dbcon.php';
session_start();

// Ensure the user is logged in
$email = $_SESSION['email'];
if (!$email) {
    echo "Please log in to update your profile.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $phone_number = trim($_POST['phone_number']);
    $date_of_birth = trim($_POST['date_of_birth']);
    $address = trim($_POST['address']);

    // Update users table
    $stmt = $con->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param('si', $username, $user_id);
    $stmt->execute();
    $stmt->close();

    // Check if userdata exists for this user
    $stmt = $con->prepare("SELECT id FROM userdata WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update userdata
        $stmt->close();
        $stmt = $con->prepare("UPDATE userdata SET full_name = ?, phone_number = ?, date_of_birth = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param('ssssi', $full_name, $phone_number, $date_of_birth, $address, $user_id);
        $stmt->execute();
    } else {
        // Insert userdata
        $stmt->close();
        $stmt = $con->prepare("INSERT INTO userdata (user_id, full_name, email, phone_number, date_of_birth, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $user_id, $full_name, $email, $phone_number, $date_of_birth, $address);
        $stmt->execute();
    }
    $stmt->close();
    header("Location: profile.php");
    exit();
} else {
    echo "Invalid request.";
    exit();
}

?>
