<?php
include 'config.php';

$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['search']);
    
    $searchQuery = "SELECT * FROM articles WHERE title LIKE '%$searchTerm%' OR content LIKE '%$searchTerm%'";
    $searchResults = $conn->query($searchQuery)->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>EDM Arena - Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>

    <link rel="stylesheet" href="css/search.css">
    <script>
        function toggleDropdown(event) {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
            event.stopPropagation(); 
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user-icon') && !event.target.matches('.user-icon *')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-black text-white">
    
<header class="flex justify-between items-center p-6">
        <div class="flex items-center">
            <img alt="EDM Arena Community Logo" class="h-12 mr-2" src="asset/logo.svg"/>
            <h1 class="text-2xl font-bold">EDM Arena Community</h1>
        </div>
        <nav class="hidden md:flex space-x-8">
            <a class="hover:text-gray-400" href="index.php">EDM Arena</a>
            <a class="hover:text-gray-400" href="community.php#latest-articles">Latest Articles</a>
            <a class="hover:text-gray-400" href="community.php#discussiontopics">Discussion Topics</a>
            <a class="hover:text-gray-400" href="community.php#trending-threads">Trending Threads</a>
            <a class="hover:text-gray-400" href="community.php#featured-members">Featured Members</a>
        </nav>
        <div class="relative">
            <div class="account dropdown">
                <div class="user-icon" onclick="toggleDropdown(event)">
                    <i class="fas fa-user"></i>
                </div>
                <div class="dropdown-content" id="dropdownContent">
                    <a href="#"><i class="fas fa-user-circle"></i> My Account</a>
                    <a href="#"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="p-6">
        <h1 class="text-4xl md:text-6xl font-bold text-center mb-8">Search Results</h1>
        <div class="mb-8 search-bar">
            <form method="POST" action="">
                <input class="w-full p-2 rounded-lg bg-gray-700 text-white" type="text" name="search" placeholder="Search..." required />
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $result): ?>
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                        <?php if (!empty($result['image_url'])): ?>
                            <img src="<?php echo $result['image_url']; ?>" alt="<?php echo $result['title']; ?>" class="h-48 w-full object-cover rounded-lg mb-4" />
                        <?php endif; ?>
                        <h2 class="text-2xl font-bold mb-2"><?php echo $result['title']; ?></h2>
                        <p class="mb-4"><?php echo substr($result['content'], 0, 100) . '...'; ?></p>
                        <a href="article.php?id=<?php echo $result['id']; ?>" class="bg-transparent border border-white text-white px-6 py-2 rounded-full hover:bg-white hover:text-black transition">Read more</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No results found.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer class="footer">
        <div class="logo">
            <img alt="EDM Arena Community Logo" src="asset/logo.svg"/>
            <span>EDM Arena Community</span>
        </div>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="links">
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
        <p>© 2024 EDM Arena. All rights reserved.</p>
    </footer>
</body>
</html>