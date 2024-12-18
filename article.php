<?php
include 'config.php'; 

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; 
    header("Location: signin.php");
    exit();
}

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$article_query = $conn->prepare("SELECT a.*, u.full_name, u.profile_picture FROM articles a JOIN users u ON a.author_id = u.id WHERE a.id = ?");
$article_query->bind_param("i", $article_id);
$article_query->execute();
$article_result = $article_query->get_result();
$article = $article_result->fetch_assoc();

if (!$article) {
    echo "Article not found.";
    exit();
}

$category_id = $article['category_id'];

$related_articles_query = $conn->prepare("SELECT * FROM articles WHERE category_id = ? AND id != ? ORDER BY published_at DESC LIMIT 3");
$related_articles_query->bind_param("ii", $category_id, $article_id);

if ($related_articles_query->execute()) {
    $related_articles_result = $related_articles_query->get_result();
} else {
    $related_articles_result = null; 
}

$comments_query = $conn->prepare("SELECT c.*, u.full_name, u.profile_picture FROM comments c JOIN users u ON c.user_id = u.id WHERE c.discussion_id = ?");
$comments_query->bind_param("i", $article_id);
$comments_query->execute();
$comments_result = $comments_query->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (isset($_SESSION['user_id'])) {
        $comment_content = $_POST['comment'];
        $user_id = $_SESSION['user_id']; 
        $insert_comment_query = $conn->prepare("INSERT INTO comments (discussion_id, user_id, content) VALUES (?, ?, ?)");
        $insert_comment_query->bind_param("iis", $article_id, $user_id, $comment_content);
        $insert_comment_query->execute();
        header("Location: article.php?id=" . $article_id);
        exit();
    } else {
        echo "<script>alert('You must be logged in to comment.');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; 
        $insert_like_query = $conn->prepare("INSERT INTO likes (discussion_id, user_id) VALUES (?, ?)");
        $insert_like_query->bind_param("ii", $article_id, $user_id);
        $insert_like_query->execute();
        header("Location: article.php?id=" . $article_id);
        exit();
    } else {
        echo "<script>alert('You must be logged in to like an article.');</script>";
    }
}

$likes_query = $conn->prepare("SELECT COUNT(*) as total_likes FROM likes WHERE discussion_id = ?");
$likes_query->bind_param("i", $article_id);
$likes_query->execute();
$likes_result = $likes_query->get_result();
$likes_count = $likes_result->fetch_assoc()['total_likes'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Article Detail - EDM Arena Community</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="css/article.css?v=1.0">
    <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        function toggleDropdown(event) {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = dropdownContent.style display === "block" ? "none" : "block";
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

        function toggleShareMenu() {
            var shareMenu = document.getElementById('share-menu');
            shareMenu.style.display = shareMenu.style.display === 'none' || shareMenu.style.display === '' ? 'block' : 'none';
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
        <a class="hover:text-gray-400" href="community.php#latest-discussions">Latest Discussions</a>
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
                <?php if (isset($_SESSION['full_name'])): ?>
                    <a href="myaccount.php"><i class="fas fa-user"></i> My Account</a>
                    <a href="?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="signin.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<main class="container mx-auto p-6 max-w-4xl">
    <article class="mb-8">
        <h1 class="text-4xl font-bold mb-4"><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="flex items-center mb-4">
            <img alt="Author's profile picture" class="w-12 h-12 rounded-full mr-4" src="<?php echo htmlspecialchars($article['profile_picture']); ?>"/>
            <div>
                <p class="text-lg font-semibold"><?php echo htmlspecialchars($article['full_name']); ?></p>
                <p class="text-gray-400">Published on <?php echo date('F j, Y', strtotime($article['published_at'])); ?></p>
            </div>
        </div>
        <img alt="Main image for the article" class="article-image" src="<?php echo htmlspecialchars($article['image_url']); ?>"/>        
        <div class="prose prose-lg text-gray-300">
            <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
        </div>
        <div class="flex items-center mt-4 space-x-4 relative">
            <form method="POST" action="">
                <button type="submit" name="like" id="like-button" class="flex items-center text-white hover:text-gray-400">
                    <i class="fas fa-thumbs-up mr-2"></i>
                    <span id="like-count"><?php echo $likes_count; ?></span>
                </button>
            </form>
            <button class="flex items-center text-white hover:text-gray-400" onclick="toggleShareMenu()">
                <i class="fas fa-share-alt mr-2"></i>
                Share
            </button>
            <div class="share-menu" id="share-menu" style="display:none;">
                <a href="https://www.facebook.com/sharer/sharer.php?u=YOUR_URL" target="_blank"><i class="fab fa -facebook-f mr-2"></i> Facebook</a>
                <a href="https://twitter.com/intent/tweet?url=YOUR_URL&text=Check%20out%20this%20article!" target="_blank"><i class="fab fa-twitter mr-2"></i> Twitter</a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=YOUR_URL&title=<?php echo urlencode($article['title']); ?>&summary=Check%20out%20this%20article!" target="_blank"><i class="fab fa-linkedin-in mr-2"></i> LinkedIn</a>
                <a href="mailto:?subject=I%20wanted%20to%20share%20this%20article%20with%20you&body=Check%20out%20this%20article:%20YOUR_URL" target="_blank"><i class="fas fa-envelope mr-2"></i> Email</a>
            </div>
        </div>
    </article>
    <section class="mb-8">
        <h2 class="text-3xl font-bold mb-4">Comments</h2>
        <div id="comment-section">
            <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div class="bg-gray-800 p-4 rounded-lg mb-4">
                <div class="flex items-center mb-2">
                    <img alt="Commenter's profile picture" class="w-10 h-10 rounded-full mr-4" src="<?php echo htmlspecialchars($comment['profile_picture']); ?>"/>
                    <div>
                        <p class="text-lg font-semibold"><?php echo htmlspecialchars($comment['full_name']); ?></p>
                        <p class="text-gray-400">Posted on <?php echo date('F j, Y', strtotime($comment['created_at'])); ?></p>
                    </div>
                </div>
                <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-4">
            <form method="POST" action="">
                <textarea id="comment-text" name="comment" class="w-full p-2 rounded-lg bg-gray-800 text-white" rows="4" placeholder="Add a comment..."></textarea>
                <button type="submit" class="btn-transparent mt-2">Post Comment</button>
            </form>
        </div>
    </section>
    <section class="mb-8">
        <h2 class="text-3xl font-bold mb-4">Related Articles</h2>
        <div class="scroll-container">
            <?php while ($related_article = $related_articles_result->fetch_assoc()): ?>
            <div class="related-article bg-gray-800 p-4 rounded-lg min-w-[300px]">
                <img alt="Related Article Title" class="w-full h-48 object-cover rounded-lg mb-4" src="<?php echo htmlspecialchars($related_article['image_url']); ?>"/>
                <h3 class="text-xl font-semibold mb-2">
                    <a class="hover:underline" href="article.php?id=<?php echo $related_article['id']; ?>"><?php echo htmlspecialchars($related_article['title']); ?></a>
                </h3>
                <p class="text-gray-400"><?php echo substr(htmlspecialchars($related_article['content']), 0, 100) . '...'; ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    <div class="text-center">
    <a href="community.php" class="btn-transparent">Back to EDM Arena Community</a>
    <a href="index.php" class="btn-transparent ml-4">Back to EDM Arena</a>
</div>
</main>
<footer class="footer">
    <div class="logo">
        <img alt="Logo" src="asset/logo.svg"/>
        <span> EDM Arena</span>
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