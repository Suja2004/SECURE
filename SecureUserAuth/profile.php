<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); 
    exit();
}

// Include the database connection file
include_once 'dbcon.php';

// Initialize variables to store user data
$user_id = $username = $user_email = $full_name = $phone_number = $date_of_birth = $address = '';

// Get the logged-in user's email
$email = $_SESSION['email'];

// Query to fetch user data
$query = "
    SELECT 
        u.id,
        u.username, 
        u.email AS user_email, 
        ud.full_name, 
        ud.phone_number, 
        ud.date_of_birth, 
        ud.address
    FROM 
        users u
    LEFT JOIN 
        userdata ud
    ON 
        u.id = ud.user_id
    WHERE 
        u.email = ?
";

if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $username, $user_email, $full_name, $phone_number, $date_of_birth, $address);
    $stmt->fetch();
    $stmt->close();
} else {
    // Error handling example: show error message in popup
    echo '<script>showPopup("Failed to fetch user data.")</script>';
    exit;
}

// Default empty values if userdata is not present
$full_name = $full_name ?? '';
$phone_number = $phone_number ?? '';
$date_of_birth = $date_of_birth ?? '';
$address = $address ?? '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="profile-page">
        <div class="profile-page-content">
            <h2>Welcome to Your Profile Page</h2>
            <div class="profile">
                <button id="edit-button" class="btn" onclick="enableEditing()">Update Profile</button>

                <form class="update-profile-form" id="profile-form" action="update_profile.php" method="POST">
                    <div class="edit-btns">
                        <input id="save-button" class="btn" type="submit" value="Save Profile" style="display: none;">
                        <button id="cancel-button" class="btn" type="button" onclick="cancelUpdate()" style="display:none ;">Cancel</button>
                    </div>

                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <div>
                        <label>Username:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    </div>
                    <div>
                        <label>Full Name:</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly>
                    </div>
                    <div>
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                    </div>
                    <div>
                        <label>Phone Number:</label>
                        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" readonly>
                    </div>
                    <div>
                        <label>Date of Birth:</label>
                        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" readonly>
                    </div>
                    <div>
                        <label>Address:</label>
                        <textarea name="address" readonly><?php echo htmlspecialchars($address); ?></textarea>
                    </div>

                </form>
                    <div class="account">
                <button class="btn" onclick="showLogoutPopup()">Log Out</button>
                <button class="btn" onclick="showDeletePopup()">Delete Account</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="logout-popup" class="popup">
        <div class="popup-content">
            <p>Are you sure you want to logout?</p>
            <form class="logout-form" action="logout.php" method="post">
                <button class="btn" type="submit">Logout</button>
                <button class="btn" type="button" onclick="hideLogoutPopup()">Cancel</button>
            </form>
        </div>
    </div>
    <div id="delete-popup" class="popup">
        <div class="popup-content">
            <p>Are you sure you want to Delete or Account?</p>
            <form class="delete-form" action="delete.php" method="post">
                <button class="btn" type="submit">Delete</button>
                <button class="btn" type="button" onclick="hideDeletePopup()">Cancel</button>
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
    
function showLogoutPopup() {
    document.getElementById("logout-popup").style.display = "block";
}

function hideLogoutPopup() {
    document.getElementById("logout-popup").style.display = "none";
}

function showDeletePopup() {
    document.getElementById("delete-popup").style.display = "block";
}

function hideDeletePopup() {
    document.getElementById("delete-popup").style.display = "none";
}

        function enableEditing() {
            const formElements = document.querySelectorAll('#profile-form input, #profile-form textarea');
            formElements.forEach(element => element.removeAttribute('readonly'));
            document.getElementById('edit-button').style.display = 'none';
            document.getElementById('save-button').style.display = 'inline';
            document.getElementById('cancel-button').style.display = 'inline';
        }

        function cancelUpdate() {
            const formElements = document.querySelectorAll('#profile-form input, #profile-form textarea');
            formElements.forEach(element => element.setAttribute('readonly', true));
            document.getElementById('edit-button').style.display = 'inline';
            document.getElementById('save-button').style.display = 'none';
            document.getElementById('cancel-button').style.display = 'none';
        }
        
</script>
</html>