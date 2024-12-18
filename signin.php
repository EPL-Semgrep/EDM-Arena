<?php
include 'config.php';

session_start(); 

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];

            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
            unset($_SESSION['redirect_url']); 

            header("Location: $redirect_url");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email address.";
    }
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .fadeIn {
            animation: fadeIn 1.5s forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background: none;
    border: none;
    color: #fff;
    font-size: 24px;
    cursor: pointer;
}
    </style>
</head>
<body class="bg-gray-900 text-white">
<button class="back-button" onclick="goBack()">
        <i class="fas fa-arrow-left"></i>
    </button>

<div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('asset/AV_bg.png');">
    <div class="bg-white bg-opacity-15 p-10 rounded-lg shadow-lg max-w-md w-full fadeIn">
        <div class="text-center mb-6">
            <img alt="Company logo with a modern design" class="mx-auto mb-4" height="100" src="asset/logo.svg" width="100"/>
            <h2 class="text-3xl font-bold text-white">Welcome Back!</h2>
            <p class="text-gray-300">Sign in to continue</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="text-red-500 mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="email" class="block text-white mb-2">Email address</label>
                <div class="flex items-center bg-gray-700 rounded">
                    <span class="px-3 text-gray-400"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="w-full py-2 px-3 bg-gray-700 text-white rounded-r focus:outline-none" placeholder="Email address" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white mb-2">Password</label>
                <div class="flex items-center bg-gray-700 rounded">
                    <span class="px-3 text-gray-400"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="w-full py-2 px-3 bg-gray-700 text-white rounded-r focus:outline-none" placeholder="Password" required>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" class="form-checkbox text-blue-500">
                <label for="remember" class="ml-2 text-white">Remember me</label>
            </div>
            <button type="submit" class="w-full py-2 bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-600 hover:to-blue-700 text-white font-bold rounded transition duration-300">Sign In</button>
            <a href="#" class="block text-center mt-3 text-white hover:underline">Forgot password?</a>
            <a href="signup.php" class="block text-center mt-3 text-white hover:underline">Create an Account</a>
        </form>
    </div>
</div>
<script>
            function goBack() {
            if (document.referrer) {
                window.location.href = 'index.php'; 
            } else {
                window.location.replace('index.php'); 
            }
        }
</script>
</body>
</html>