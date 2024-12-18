<?php
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $full_name = $conn->real_escape_string($full_name);
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);
    $confirm_password = $conn->real_escape_string($confirm_password);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                header("Location: signin.php");
                exit();
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
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
            <h2 class="text-3xl font-bold text-white">Create Your Account</h2>
            <p class="text-gray-300">Fill in the details below</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="text-red-500 mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="full_name" class="block text-white mb-2">Full Name</label>
                <div class="flex items-center bg-gray-700 rounded">
                    <span class="px-3 text-gray-400"><i class="fas fa-user"></i></span>
                    <input type="text" id="full_name" name="full_name" class="w-full py-2 px-3 bg-gray-700 text-white rounded-r focus:outline-none" placeholder="Full Name" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white mb-2"> Email address</label>
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
            <div class="mb-4">
                <label for="confirm_password" class="block text-white mb-2">Confirm Password</label>
                <div class="flex items-center bg-gray-700 rounded">
                    <span class="px-3 text-gray-400"><i class="fas fa-lock"></i></span>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full py-2 px-3 bg-gray-700 text-white rounded-r focus:outline-none" placeholder="Confirm Password" required>
                </div>
            </div>
            <button type="submit" class="w-full py-2 bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-600 hover:to-blue-700 text-white font-bold rounded transition duration-300">Sign Up</button>
            <a href="signin.php" class="block text-center mt-3 text-white hover:underline">Already have an account? Sign In</a>
        </form>
    </div>
</div>
<script>
            function goBack() {
            if (document.referrer) {
                window.location.href = document.referrer; 
            } else {
                window.location.replace('http://localhost/edmarena/index.php'); 
            }
        }
</script>
</body>
</html>