<?php
include 'config.php';

$latestDiscussions = $conn->query("SELECT * FROM latestdiscussions");
$discussionTopics = $conn->query("SELECT * FROM discussiontopics");
$featuredMembers = $conn->query("SELECT * FROM featuredmembers");
$forumStatistics = $conn->query("SELECT * FROM forumstatistics");
$trendingThreads = $conn->query("SELECT * FROM trendingthreads");

$statistics = $forumStatistics->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>EDM Arena Community</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="css/community.css">
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
    </script>
</head>
<body>
<header>
    <img alt="EDM Arena Community Logo" src="https://arvalen.github.io/Web/img/va.png"/>
    <h1>EDM Arena Community</h1>
</header>
<nav>
    <a href="index.php">EDM Arena</a>
    <a href="#latest-discussions">Latest Discussions</a>
    <a href="#discussiontopics">Discussion Topics</a>
    <a href="#trending-threads">Trending Threads</a>
    <a href="#featured-members">Featured Members</a>
</nav>
<div class="container mx-auto p-6">
    <section class="form-section mb-8" id="form-section" style="display: none;">
        <h2 class="section-title">Create a New Post</h2>
        <form>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="post-title">Post Title</label>
                <input class="w-full p-2 rounded-lg bg-gray-700 text-white" id="post-title" placeholder="Enter the title of your post" type="text"/>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="post-content">Post Content</label>
                <textarea class="w-full p-2 rounded-lg bg-gray-700 text-white" id="post-content" placeholder="Write your post content here" rows="6"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-semibold mb-2" for="post-image">Upload Image</label>
                <input class="w-full p-2 rounded-lg bg-gray-700 text-white" id="post-image" type="file"/>
            </div>
            <button class="bg-green-500 p-2 rounded-lg text-white font-semibold hover:bg-green-600" type="submit">Submit Post</button>
        </form>
    </section>
    <div class="main-content">
    <section class="discussion-section mb-8" id="latest-discussions">
    <h2 class="section-title">Latest Discussions</h2>
    <?php while ($discussion = $latestDiscussions->fetch_assoc()): ?>
        <div class="discussion-item">
            <img alt="<?= $discussion['title'] ?>" class="w-24 h-24 rounded-lg" src="<?= $discussion['image_url'] ?>" width="100" height="100"/>
            <div>
                <h3 class="text-2xl font-semibold">
                    <a href="article.php?id=<?= $discussion['id'] ?>" class="text-blue-500 hover:underline">
                        <?= $discussion['title'] ?>
                    </a>
                </h3>
                <p class="text-gray-400"><?= $discussion['description'] ?></p>
            </div>
        </div>
    <?php endwhile; ?>
</section>
     <section class="stats-section mb-8" id="forum-statistics">
            <h2 class="section-title">Forum Statistics</h2>
            <div class="stat-item">
                <img alt="Icon representing total members" class="w-16 h-16 rounded-full" src="<?= $statistics['image_url'] ?>" width="100" height="100"/>
                <div>
                    <h3 class="text-xl font-semibold">Total Members</h3>
                    <p class="text-gray-400"><?= $statistics['total_members'] ?>+</p>
                </div>
            </div>
            <div class="stat-item">
                <img alt="Icon representing total posts" class="w-16 h-16 rounded-full" src="<?= $statistics['image_url'] ?>" width="100" height="100"/>
                <div>
                    <h3 class="text-xl font-semibold">Total Posts</h3>
                    <p class="text-gray-400"><?= $statistics['total_posts'] ?>+</p>
                </div>
            </div>
            <div class="stat-item">
                <img alt="Icon representing total discussions" class="w-16 h-16 rounded-full" src="<?= $statistics['image_url'] ?>" width="100" height="100"/>
                <div>
                    <h3 class="text-xl font-semibold">Total Discussions</h3>
                    <p class="text-gray-400"><?= $statistics['total_discussions'] ?>+</p>
                </div>
            </div>
            <div class="stat-item">
                <img alt="Icon representing total events" class="w-16 h-16 rounded-full" src="<?= $statistics['image_url'] ?>" width="100" height="100"/>
                <div>
                    <h3 class="text-xl font-semibold">Total Events</h3>
                    <p class="text-gray-400"><?= $statistics['total_events'] ?>+</p>
                </div>
            </div>
        </section>
    </div>
    <section class="topics mb-8" id="discussiontopics">
        <h2 class="section-title">Discussion Topics</h2>
        <?php while ($topic = $discussionTopics->fetch_assoc()): ?>
            <div class="topic">
                <img alt="<?= $topic['title'] ?>" class="w-16 h-16 rounded-full" src="<?= $topic['image_url'] ?>" width="100" height="100"/>
                <div>
                    <h3 class="text-xl font-semibold"><?= $topic['title'] ?></h3>
                    <p class="text-gray-400"><?= $topic['description'] ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
    <section class="trending mb-8" id="trending-threads">
        <h2 class="section-title">Trending Threads</h2>
        <?php while ($thread = $trendingThreads->fetch_assoc()): ?>
            <div class="thread">
                <img alt="<?= $thread['title'] ?>" class="w-16 h-16 rounded-full" src="<?= $thread['image_url'] ?>" width="100" height="100"/>
                <div>
                    <h3 class="text-xl font-semibold"><?= $thread['title'] ?></h3>
                    <p class="text-gray-400"><?= $thread['comments'] ?> comments | <?= $thread['likes'] ?> likes</p>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
    <section class="members-section"id="featured-members">
        <h2 class="section-title">Featured Members</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php while ($member = $featuredMembers->fetch_assoc()): ?>
                <div class="member-item">
                    <img alt="<?= $member['username'] ?>" class="w-16 h-16 rounded-full" src="<?= $member['profile_picture'] ?>" width="100" height="100"/>
                    <div>
                        <h3 class="text-xl font-semibold"><?= $member['username'] ?></h3>
                        <p class="text-gray-400">"<?= $member['quote'] ?>"</p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>
<div class=" create-post-icon" id="create-post-icon" onclick="toggleForm()">
   <i class="fas fa-plus fa-2x"></i>
</div>
<div class="footer">
    <div class="logo">
        <img src="https://arvalen.github.io/Web/img/va.png" alt="Logo">
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
    <p>&copy; 2024 EDM Arena. All rights reserved.</p>
</div>
</body>
</html>


<?php
$conn->close();
?>