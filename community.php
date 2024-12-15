<?php
include 'config.php';

$forumStatistics = $conn->query("SELECT * FROM forumstatistics LIMIT 1")->fetch_assoc();
$latestDiscussions = $conn->query("SELECT * FROM latestdiscussions ORDER BY id DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$discussionTopics = $conn->query("SELECT * FROM discussiontopics ORDER BY id DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$trendingThreads = $conn->query("SELECT * FROM trendingthreads ORDER BY likes DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$featuredMembers = $conn->query("SELECT * FROM featuredmembers ORDER BY id DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$latestArticles = $conn->query("SELECT * FROM articles ORDER BY published_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>EDM Arena Community</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="css/community.css?v=1.0">
    <link href="https://raw.githubusercontent.com/arvalen/Web/8a053a651c7b144d38cf3a91081211101d381e68/PWEB%20-%20ETS/Landing%20Page%20Store/img/logo.svg" rel="shortcut icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleForm() {
            var formSection = document.getElementById('form-section');
            var createPostIcon = document.getElementById('create-post-icon');
            if (formSection.style.display === 'none' || formSection.style.display === '') {
                formSection.style.display = 'block';
                createPostIcon.classList.add('rotate');
            } else {
                formSection.style.display = 'none';
                createPostIcon.classList.remove('rotate');
            }
        }

        function toggleDropdown(event) {
            if (event.target.closest('.account-button')) {
                var dropdownMenu = document.getElementById('dropdown-menu');
                if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                    dropdownMenu.style.display = 'block';
                } else {
                    dropdownMenu.style.display = 'none';
                }
            }
        }

        window.onclick = function(event) {
            if (!event.target.closest('.account-button')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === 'block') {
                        openDropdown.style.display = 'none';
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-black text-white">
    <header class="flex justify-between items-center p-6">
        <div class="flex items-center">
            <img alt="EDM Arena Community Logo" class="h-12 mr-2" src="https://arvalen.github.io/Web/img/va.png"/>
            <h1 class="text-2xl font-bold">EDM Arena Community</h1>
        </div>
        <nav class="hidden md:flex space-x-8">
            <a class="hover:text-gray-400" href="index.php">EDM Arena</a>
            <a class="hover:text-gray-400" href="#latest-discussions">Latest Discussions</a>
            <a class="hover:text-gray-400" href="#discussiontopics">Discussion Topics</a>
            <a class="hover:text-gray-400" href="#trending-threads">Trending Threads</a>
            <a class="hover:text-gray-400" href="#featured-members">Featured Members</a>
        </nav>
        <div class="relative">
            <div class="account-button bg-gray-800 text-white p-3 rounded-full flex items-center" onclick="toggleDropdown(event)">
                <i class="fas fa-user"></i>
            </div>
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="#"><i class="fas fa-user-circle mr-2"></i> My Account</a>
                <a href="#"><i class="fas fa-sign-in -alt mr-2"></i> Login</a>
                <a href="#"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
            </div>
        </div>
    </header>
    <main class="container mx-auto p-6">
        <div class="mb-8 search-bar">
            <input type="text" placeholder="Search..." />
            <button><i class="fas fa-search"></i></button>
        </div>
        <section class="mb-8" id="forum-statistics">
            <h2 class="text-3xl font-bold mb-4">Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <img alt="Icon representing total members" class="w-12 h-12 rounded-full mr-4" src="https://storage.googleapis.com/a1aa/image/QabMQdA24EonOZhReU5TbwKW9II9y7CLkuBpWJoUh7Ayod9JA.jpg"/>
                    <div>
                        <h3 class="text-lg font-semibold">Total Members</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_members']; ?></p>
                    </div>
                </div>
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <img alt="Icon representing total posts" class="w-12 h-12 rounded-full mr-4" src="https://storage.googleapis.com/a1aa/image/6k2AMf5Pqz2DKCWnIecZVlYnAlIVPA2TrYjFYegCxRafFtrPB.jpg"/>
                    <div>
                        <h3 class="text-lg font-semibold">Total Posts</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_posts']; ?></p>
                    </div>
                </div>
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <img alt="Icon representing total discussions" class="w-12 h-12 rounded-full mr-4" src="https://storage.googleapis.com/a1aa/image/pI9Xc1LrGjqXJV5CZbrNmq8SSNgrpIbfed4Lcew6RpNHj21nA.jpg"/>
                    <div>
                        <h3 class="text-lg font-semibold">Total Discussions</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_discussions']; ?></p>
                    </div>
                </div>
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <img alt="Icon representing total events" class="w-12 h-12 rounded-full mr-4" src="https://storage.googleapis.com/a1aa/image/VFK3spNPib4LNNJKrdLBjwOMvKAIRuFWGY0vE8eVfguoR76TA.jpg"/>
                    <div>
                        <h3 class="text-lg font-semibold">Total Events</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_events']; ?></p>
                    </div>
                </div>
            </div>
        </section>
        <section class="mb-8" id="latest-discussions">
    <h2 class="text-3xl font-bold mb-4">Latest Articles</h2>
    <div class="scroll-container">
        <?php foreach ($latestArticles as $article): ?>
        <div class="discussion-item bg-gray-800 p-4 rounded-lg min-w-[300px]">
            <img alt="Article Title" class="w-full h-48 object-cover rounded-lg mb-4" src="<?php echo $article['image_url']; ?>"/>
            <h3 class="text-xl font-semibold mb-2">
                <a class="text-blue-500 hover:underline" href="article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a>
            </h3>
            <p class="text-gray-400"><?php echo substr($article['content'], 0, 100) . '...'; ?></p> <!-- Display a snippet of the content -->
        </div>
        <?php endforeach; ?>
    </div>
</section>
        <section class="mb-8" id="discussiontopics">
            <h2 class="text-3xl font-bold mb-4">Discussion Topics</h2>
            <div class="scroll-container">
                <?php foreach ($discussionTopics as $topic): ?>
 <div class="topic bg-gray-800 p-4 rounded-lg flex items-center min-w-[300px]">
                    <img alt="Topic Title" class="w-16 h-16 rounded-full mr-4" src="<?php echo $topic['image_url']; ?>"/>
                    <div>
                        <h3 class="text-xl font-semibold"><?php echo $topic['title']; ?></h3>
                        <p class="text-gray-400"><?php echo $topic['description']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="mb-8" id="trending-threads">
            <h2 class="text-3xl font-bold mb-4">Trending Threads</h2>
            <div class="scroll-container">
                <?php foreach ($trendingThreads as $thread): ?>
                <div class="thread bg-gray-800 p-4 rounded-lg flex items-center min-w-[300px]">
                    <img alt="Thread Title" class="w-16 h-16 rounded-full mr-4" src="<?php echo $thread['image_url']; ?>"/>
                    <div>
                        <h3 class="text-xl font-semibold"><?php echo $thread['title']; ?></h3>
                        <p class="text-gray-400"><?php echo $thread['comments']; ?> comments | <?php echo $thread['likes']; ?> likes</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="mb-8" id="featured-members">
            <h2 class="text-3xl font-bold mb-4">Featured Members</h2>
            <div class="scroll-container">
                <?php foreach ($featuredMembers as $member): ?>
                <div class="member-item bg-gray-800 p-4 rounded-lg flex items-center min-w-[300px]">
                    <img alt="Member Username" class="w-16 h-16 rounded-full mr-4" src="<?php echo $member['profile_picture']; ?>"/>
                    <div>
                        <h3 class="text-xl font-semibold"><?php echo $member['username']; ?></h3>
                        <p class="text-gray-400">"<?php echo $member['quote']; ?>"</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <div class="create-post-icon bg-green-500 text-white" id="create-post-icon" onclick="toggleForm()">
        <i class="fas fa-plus fa-2x"></i>
    </div>
    <footer class="footer">
        <div class="logo">
            <img alt="Logo" src="https://raw.githubusercontent.com/arvalen/Web/8a053a651c7b144d38cf3a91081211101d381e68/PWEB%20-%20ETS/Landing%20Page%20Store/img/logo.svg"/>
            <span>EDM Arena</span>
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