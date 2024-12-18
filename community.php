<?php
include 'config.php';

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['post-title'];
    $content = $_POST['post-content'];
    $author_id = 1; // Assuming a logged-in user with ID 1
    $category_id = 1; 
    $image_url = null;

    if (isset($_FILES['post-image']) && $_FILES['post-image']['error'] == 0) {
        $target_dir = "uploads/"; 
        $target_file = $target_dir . basename($_FILES["post-image"]["name"]);
        if (move_uploaded_file($_FILES["post-image"]["tmp_name"], $target_file)) {
            $image_url = $target_file; 
        } else {
            $message = "Error uploading image.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id, image_url, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisi", $title, $content, $author_id, $image_url, $category_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Post baru berhasil dibuat.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); 
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$featuredMembersResult = $conn->query("
    SELECT 
        u.id AS user_id,
        u.full_name,
        u.profile_picture,
        u.quote,
        COUNT(DISTINCT a.id) AS total_articles,
        COUNT(DISTINCT c.id) AS total_comments,
        COUNT(DISTINCT l.id) AS total_likes,
        (COUNT(DISTINCT a.id) + COUNT(DISTINCT c.id) + COUNT(DISTINCT l.id)) AS total_interactions
    FROM users u
    LEFT JOIN articles a ON u.id = a.author_id
    LEFT JOIN comments c ON u.id = c.user_id
    LEFT JOIN likes l ON u.id = l.user_id
    GROUP BY u.id
    ORDER BY total_interactions DESC
    LIMIT 5
");

$featuredMembers = $featuredMembersResult ? $featuredMembersResult->fetch_all(MYSQLI_ASSOC) : [];

$forumStatistics = $conn->query("SELECT * FROM forumstatistics LIMIT 1")->fetch_assoc();
$discussionTopics = $conn->query("SELECT * FROM discussions ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$trendingDiscussions = $conn->query("
    SELECT d.*, COUNT(r.id) AS replies_count 
    FROM discussions d 
    LEFT JOIN replies r ON d.id = r.discussion_id 
    GROUP BY d.id 
    ORDER BY replies_count DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);
$latestArticles = $conn->query("SELECT * FROM articles ORDER BY published_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$totalMembers = $conn->query("SELECT COUNT(*) as total_members FROM users")->fetch_assoc()['total_members'];
$totalPosts = $conn->query("SELECT COUNT(*) as total_posts FROM articles")->fetch_assoc()['total_posts'];
$totalDiscussions = $conn->query("SELECT COUNT(*) as total_discussions FROM discussions")->fetch_assoc()['total_discussions'];
$totalEvents = $conn->query("SELECT COUNT(*) as total_events FROM festivals")->fetch_assoc()['total_events'];

$conn->query("UPDATE forumstatistics SET total_members = $totalMembers, total_posts = $totalPosts, total_discussions = $totalDiscussions, total_events = $totalEvents WHERE id = 1");

$forumStatistics = $conn->query("SELECT * FROM forumstatistics LIMIT 1")->fetch_assoc();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>EDM Arena Community</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="css/community.css?v=1.0">
    <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>
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
            <a class="hover:text-gray-400" href="#latest-articles">Latest Articles</a>
            <a class="hover:text-gray-400" href="#discussiontopics">Discussion Topics</a>
            <a class="hover:text-gray-400" href="#trending-discussions">Trending Discussions</a>
            <a class="hover:text-gray-400" href="#featured-members">Featured Members</a>
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
    <main class="container mx-auto p-6">
            <div class="mb-8 search-bar">
                <form method="POST" action="search.php">
                    <input type="text" name="search" placeholder="Search..." required />
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        <section class="form-section mb-8" id="form-section" style="display: none;">
            <h2 class="section-title">Create a New Post</h2>
            <form id="create-post-form" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-lg font-semibold mb-2" for="post-title">Post Title</label>
                    <input class="w-full p-2 rounded-lg bg-gray-700 text-white" id="post-title" name="post-title" placeholder="Enter the title of your post" type="text" required/>
                </div>
                <div class="mb-4">
                    <label class="block text-lg font-semibold mb-2" for="post-content">Post Content</label>
                    <textarea class="w-full p-2 rounded-lg bg-gray-700 text-white" id="post-content" name="post-content" placeholder="Write your post content here" rows="6" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-lg font-semibold mb-2" for="post-image">Upload Image</label>
                    <input class="w-full p-2 rounded-lg bg-gray-700 text-white" id="post-image" name="post-image" type="file" accept="image/*"/>
                </div>
                <button class="bg-green-500 p-2 rounded-lg text-white font-semibold hover:bg-green-600" type="submit">Submit Post</button>
            </form>
            <div id="notification" class="mt-4"><?php echo $message; ?></div>
        </section>
        <section class="mb-8" id="forum-statistics">
            <h2 class="text-3xl font-bold mb-4">Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <i class="fas fa-users fa-2x mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Total Members</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_members']; ?></p>
                    </div>
                </div>
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <i class="fas fa-pencil-alt fa-2x mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Total Posts</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_posts']; ?></p>
                    </div>
                </div>
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <i class="fas fa-comments fa-2x mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Total Discussions</h3>
                        <p class="text-gray-400"><?php echo $totalDiscussions; ?></p>
                    </div>
                </div>
                <div class="stat-item bg-gray-800 p-2 rounded-lg flex items-center">
                    <i class="fas fa-calendar-alt fa-2x mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Total Events</h3>
                        <p class="text-gray-400"><?php echo $forumStatistics['total_events']; ?></p>
                    </div>
                </div>
            </div>
        </section>
        <section class="mb-8" id="latest-articles">
            <h2 class="text-3xl font-bold mb-4">Latest Articles</h2>
            <div class="scroll-container">
                <?php foreach ($latestArticles as $article): ?>
                <div class="discussion-item bg-gray-800 p-4 rounded-lg min-w-[300px]">
                    <img alt="Article Title" class="w-full h-48 object-cover rounded-lg mb-4" src="<?php echo $article['image_url']; ?>"/>
                    <h3 class="text-xl font-semibold mb-2">
                        <a class="hover:underline" href="article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a>
                    </h3>
                    <p class="text-gray-400"><?php echo substr($article['content'], 0, 100) . '...'; ?></p>
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
                        <h3 class="text-xl font-semibold">
                            <a class="hover:underline" href="discussion.php?id=<?php echo $topic['id']; ?>"><?php echo $topic['title']; ?></a>
                        </h3>
                        <p class="text-gray-400"><?php echo substr($topic['content'], 0, 100) . '...'; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mb-8" id="trending-discussions">
            <h2 class="text-3xl font-bold mb-4">Trending Discussions</h2>
            <div class="scroll-container">
                <?php foreach ($trendingDiscussions as $discussion): ?>
                <div class="discussion bg-gray-800 p-4 rounded-lg flex items-center min-w-[300px]">
                    <img alt="Discussion Title" class="w-16 h-16 rounded-full mr-4" src="<?php echo $discussion['image_url']; ?>"/>
                    <div>
                        <h3 class="text-xl font-semibold">
                            <a class="hover:underline" href="discussion.php?id=<?php echo $discussion['id']; ?>"><?php echo $discussion['title']; ?></a>
                        </h3>
                        <p class="text-gray-400"><?php echo $discussion['replies_count']; ?> replies</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="mb-8" id="featured-members">
            <h2 class="text-3xl font-bold mb-4">Featured Members</h2>
            <div class="scroll-container">
                <?php if (!empty($featuredMembers)): ?>
                    <?php foreach ($featuredMembers as $member): ?>
                    <div class="member-item bg-gray-800 p-4 rounded-lg flex items-center min-w-[300px]">
                        <img alt="Member Username" class="w-16 h-16 rounded-full mr-4" src="<?php echo $member['profile_picture']; ?>"/>
                        <div>
                            <h3 class="text-xl font-semibold"><?php echo $member['full_name']; ?></h3>
                            <p class="text-gray-400">"<?php echo $member['quote']; ?>"</p>
                            <p class="text-gray-400">Articles: <?php echo $member['total_articles']; ?>, Comments: <?php echo $member['total_comments']; ?>, Likes: <?php echo $member['total_likes']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-400">No featured members found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <div class="create-post-icon bg-green-500 text-white" id="create-post-icon" onclick="toggleForm()">
        <i class="fas fa-plus fa-2x"></i>
    </div>
    <footer class="footer">
        <div class="logo">
            <img alt="Logo" src="asset/logo.svg"/>
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