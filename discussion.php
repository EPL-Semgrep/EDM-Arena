<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_discussion'])) {
    $title = $_POST['title'];
    $content = $_POST['message']; 
    $author_id = 1; 

    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; 
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file; 
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    $stmt = $conn->prepare("INSERT INTO discussions (title, content, author_id, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $content, $author_id, $image_url);

    if ($stmt->execute()) {
        header("Location: discussion.php");
        exit();
    } else {
        echo "<script>alert('Error posting discussion: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_reply'])) {
    $discussion_id = $_POST['discussion_id'];
    $reply_content = $_POST['reply_content'];
    $user_id = 1; 

    $stmt = $conn->prepare("INSERT INTO replies (discussion_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $discussion_id, $user_id, $reply_content);

    if ($stmt->execute()) {
        header("Location: discussion.php");
        exit();
    } else {
        echo "<script>alert('Error posting reply: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$discussions = $conn->query("
    SELECT discussions.*, users.full_name, users.profile_picture 
    FROM discussions 
    JOIN users ON discussions.author_id = users.id 
    ORDER BY discussions.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>EDM Arena Community - Discussion</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="css/discussion.css">
    <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleReplyForm(id) {
            var replyForm = document.getElementById('reply-form-' + id);
            if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                replyForm.style.display = 'block';
            } else {
                replyForm.style.display = 'none';
            }
        }

        function toggleReplies(id) {
            var replies = document.getElementById('replies-' + id);
            if (replies.style.display === 'none' || replies.style.display === '') {
                replies.style.display = 'block';
            } else {
                replies.style.display = 'none';
            }
        }
        function toggleDropdown() {
            var dropdownContent = document.getElementById("dropdownContent");
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        }

        window.onclick = function(event) {
            if (!event.target.matches ('.user-icon') && !event.target.matches('.user-icon *')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
        window.onload = function() {
            if (sessionStorage.getItem('scrollPosition')) {
                window.scrollTo(0, sessionStorage.getItem('scrollPosition'));
                sessionStorage.removeItem('scrollPosition');
            }
        };

        window.onbeforeunload = function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        };
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
    <main class="container mx-auto p-6">
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Start a New Discussion</h2>
            <form class="bg-gray-800 p-6 rounded shadow-md" method="POST" action="discussion.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-400" for="title">Title</label>
                    <input class="w-full p-2 bg-gray-700 text-white rounded mt-1" id="title" name="title" type="text" required/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400" for="message">Message</label>
                    <textarea class="w-full p-2 bg-gray-700 text-white rounded mt-1" id="message" name="message" rows="5" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400" for="image">Upload Image</label>
                    <input accept="image/*" class="w-full p-2 bg-gray-700 text-white rounded mt-1" id="image" name="image" type="file"/>
                </div>
                <button class="bg-green-500 text-white px-4 py-2 rounded" type="submit" name="post_discussion">Post</button>
            </form>
        </section>
        <section>
            <h2 class="text-xl font-semibold mb-4">Recent Discussions</h2>
            <div class="space-y-4">
                <?php while ($discussion = $discussions->fetch_assoc()): ?>
                <div class="bg-gray-800 p-6 rounded shadow-md">
                    <div class="flex items-center mb-4">
                        <img alt="Profile picture of user" class="w-12 h-12 rounded-full mr-4" src="<?php echo htmlspecialchars($discussion['profile_picture']); ?>" />
                        <div>
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($discussion['full_name']); ?></h3>
                            <p class="text-gray-400">Posted on <?php echo htmlspecialchars($discussion['created_at']); ?></p>
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($discussion['title']); ?></h4>
                    <?php if ($discussion['image_url']): ?>
                    <img alt="Discussion image" class="w-full max-w-md object-cover rounded-lg mb-4" src="<?php echo htmlspecialchars($discussion['image_url']); ?>"/>
                    <?php endif; ?>
                    <p class="text-gray-400 mb-4"><?php echo htmlspecialchars($discussion['content']); ?></p>
                    <div class="flex space-x-4">
                        <button class="text-green-500 hover:underline" onclick="toggleReplyForm(<?php echo $discussion['id']; ?>)">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                        <button class="text-green-500 hover:underline">
                            <i class="fas fa-thumbs-up"></i> Like
                        </button>
                        <button class="text-green-500 hover:underline" onclick="toggleReplies(<?php echo $discussion['id']; ?>)">
                            <i class="fas fa-comments"></i> See Replies
                        </button>
                    </div>
                    <div class="mt-4" id="reply-form-<?php echo $discussion['id']; ?>" style="display: none;">
                        <form class="bg-gray-700 p-4 rounded" method="POST" action="discussion.php">
                            <input type="hidden" name="discussion_id" value="<?php echo $discussion['id']; ?>">
                            <textarea class="w-full p-2 bg-gray-600 text-white rounded mb-2" name="reply_content" placeholder="Write your reply..." rows="3" required></textarea>
                            <button class="bg-green-500 text-white px-4 py-2 rounded" type="submit" name="post_reply">Submit Reply</button>
                        </form>
                    </div>
                    <div class="mt-4" id="replies-<?php echo $discussion['id']; ?>" style="display: none;">
                        <?php
                        $replies = $conn->query("
                            SELECT replies.*, users.full_name, users.profile_picture 
                            FROM replies 
                            JOIN users ON replies.user_id = users.id 
                            WHERE discussion_id = " . $discussion['id']
                        );
                        while ($reply = $replies->fetch_assoc()): ?>
                        <div class="bg-gray-700 p-4 rounded mb-2">
                            <div class="flex items-center mb-2">
                                <img alt="Profile picture of user" class="w-8 h-8 rounded-full mr-2" src="<?php echo htmlspecialchars($reply['profile_picture']); ?>" />
                                <div>
                                    <h4 class="text-sm font-semibold"><?php echo htmlspecialchars($reply['full_name']); ?></h4>
                                    <p class="text-gray-400 text-xs">Replied on <?php echo htmlspecialchars($reply['created_at']); ?></p>
                                </div>
                            </div>
                            <p class="text-gray-400"><?php echo htmlspecialchars($reply['content']); ?></p>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <div class="footer">
        <div class="logo">
            <img src="asset/logo.svg" alt="Logo">
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