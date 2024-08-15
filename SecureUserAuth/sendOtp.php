<?php
include_once("dbcon.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['email']) || empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit;
    }

    $email = $data['email'];

    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email exists
    if ($stmt->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid email']);
        $stmt->close();
        $con->close();
        exit;
    }

    $stmt->close();

    // Generate OTP
    $otp = mt_rand(100000, 999999);

    session_start();
    session_regenerate_id(true); // Regenerate session ID for security
    $_SESSION['otp'] = $otp;

    // Send email
    $to = $email;
    $subject = 'OTP for Password Reset';
    $message = 'Your OTP is: ' . $otp;
    $headers = 'From: no-reply@example.com' . "\r\n" . // Add From header
               'Reply-To: no-reply@example.com' . "\r\n" . 
               'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
    }

    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
