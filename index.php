<?php
include 'config.php';

session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$festivals_sql = "SELECT * FROM festivals";
$festivals_result = $conn->query($festivals_sql);

$articles_sql = "SELECT * FROM articles ORDER BY published_at DESC LIMIT 5"; 
$articles_result = $conn->query($articles_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDM Arena</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="asset/logo.svg" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="asset/logo.svg" alt="Logo">
            <span>EDM Arena</span>
        </div>
        <div class="nav-links">
            <a href="newmusic.php">NEW MUSIC</a>
            <a href="#upcoming-festivals">UPCOMING FESTIVALS & EVENTS</a>
            <a href="#latest-news">LATEST NEWS</a>
            <a href="community.php">COMMUNITY</a>
        </div>
        <div class="account dropdown">
            <div class="user-icon" onclick="toggleDropdown()">
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

    <div class="hero">
        <h1>EDM Arena</h1>
        <p>EDM Arena is one of the most electrifying electronic music events,<br>renowned for its mind-blowing stage designs and unforgettable energy.</p>
        <div class="buttons">
            <a href="#upcoming-festivals">Explore Now</a>
        </div>
    </div>

    <div class="cards-container" id="upcoming-festivals">
        <div class="upcoming">
            <h2>Upcoming<br>Festivals & Events</h2>
            <a href="#" class="calendar-button">Calendar</a>
        </div>
        <div class="cards">
            <?php while ($festival = $festivals_result->fetch_assoc()) { ?>
                <div class="card">
                    <img src="<?php echo $festival['image_url']; ?>" alt="Festival Image">
                    <div class="card-content">
                        <h2><?php echo $festival['name']; ?></h2>
                        <div class="date-location">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?php echo date('F j, Y', strtotime($festival['event_date'])); ?></span>
                        </div>
                        <div class="date-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $festival['location']; ?></span>
                        </div>
                        <div class="buttons">
                            <a href="<?php echo $festival['info']; ?>" target="_blank">
                                <button>INFO</button>
                            </a>
                            <a href="<?php echo $festival['ticket']; ?>" target="_blank">
                                <button>TICKETS</button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="latest-news" id="latest-news">
        <h2>Latest News</h2>
        <div class="news-cards">
            <?php while ($article = $articles_result->fetch_assoc()) { ?>
                <a href="article.php?id=<?php echo $article['id']; ?>" class="news-card" style="text-decoration: none; color: inherit;">
                    <img src="<?php echo $article['image_url']; ?>" alt="News Image">
                    <div class="card-content">
                        <h2><?php echo $article['title']; ?></h2>
                        <p><?php echo substr($article['content'], 0, 100) . '...'; ?></p> 
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>

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

    <script>
        function toggleDropdown() {
            var dropdownContent = document.getElementById("dropdownContent");
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
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

    <?php
    $conn->close();
    ?>
</body>
</html>