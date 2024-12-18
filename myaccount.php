<?php
session_start();
require 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fieldsToUpdate = [];
    $params = [];
    
    if (!empty($_POST['username'])) {
        $fieldsToUpdate[] = "full_name = ?";
        $params[] = $_POST['username'];
    }
    
    if (!empty($_POST['email'])) {
        $fieldsToUpdate[] = "email = ?";
        $params[] = $_POST['email'];
    }
    
    if (!empty($_POST['quote'])) {
        $fieldsToUpdate[] = "quote = ?";
        $params[] = $_POST['quote'];
    }

    if (!empty($_POST['current-password']) && !empty($_POST['new-password'])) {
        $current_password = $_POST['current-password'];
        $new_password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
        
        if (password_verify($current_password, $user['password'])) {
            $fieldsToUpdate[] = "password = ?";
            $params[] = $new_password;
        } else {
            echo "Password saat ini salah.";
        }
    }

    if (!empty($fieldsToUpdate)) {
        $updateQuery = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE id = ?";
        $params[] = $user_id;
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);
        $stmt->execute();
    }

    header('Location: myaccount.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>

    <link rel="stylesheet" href="css/myaccount.css">
</head>
<body>
    <button class="back-button" onclick="goBack()">
        <i class="fas fa-arrow-left"></i>
    </button>
    <div class="container">
        <h2>My Account</h2>
        <div class="profile-picture">
            <?php
            $profile_picture = $user['profile_picture'] ?: 'default.jpg';
            ?>
            <img alt="User  profile picture" height="100" src="<?php echo htmlspecialchars($profile_picture); ?>" width="100"/>
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($user['full_name'] ?: ''); ?>" placeholder="Enter your username"/>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name=" email" type="email" value="<?php echo htmlspecialchars($user['email'] ?: ''); ?>" placeholder="Enter your email"/>
            </div>
            <button class="password-button" id="passwordButton" type="button">Edit Password</button>
            <div class="form-group">
                <label for="quote">Quote</label>
                <textarea id="quote" name="quote" rows="3" placeholder="Your favorite quote here..."><?php echo htmlspecialchars($user['quote'] ?: ''); ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Save Changes</button>
            </div>
        </form>
    </div>
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3>Change Password</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input id="current-password" name="current-password" type="password"/>
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input id="new-password" name="new-password" type="password"/>
                </div>
                <div class="form-group">
                    <button type="submit">Save Password</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function goBack() {
            if (document.referrer) {
                window.location.href = document.referrer; 
            } else {
                window.location.replace('index.php'); 
            }
        }

        document.getElementById('passwordButton').onclick = function() {
            document.getElementById('passwordModal').style.display = 'flex';
        }

        document.getElementById('closeModal').onclick = function() {
            document.getElementById('passwordModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('passwordModal')) {
                document.getElementById('passwordModal').style.display = 'none';
            }
        }
    </script>
</body>
</html>