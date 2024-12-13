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

$news_sql = "SELECT * FROM latestnews";
$news_result = $conn->query($news_sql);
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
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="https://arvalen.github.io/Web/img/va.png" alt="Logo">
            <span>EDM Arena</span>
        </div>
        <div class="nav-links">
            <a href="#upcoming-festivals">UPCOMING FESTIVALS & EVENTS</a>
            <a href="#latest-news">LATEST NEWS</a>
            <a href="community.php">COMMUNITY</a>
        </div>
        <div class="account">
            <i class="fas fa-user"></i>
            <?php
            if (isset($_SESSION['full_name'])) {
                echo $_SESSION['full_name']; 
                echo ' <a href="?logout=true">Logout</a>'; 
            } else {
                echo '<a href="signin.php">Log In</a>';
            }
            ?>
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
        <?php while ($news = $news_result->fetch_assoc()) { ?>
            <a href="article.php?id=<?php echo $news['id']; ?>" class="news-card" style="text-decoration: none; color: inherit;">
                <img src="<?php echo $news['image_url']; ?>" alt="News Image">
                <div class="card-content">
                    <h2><?php echo $news['title']; ?></h2>
                    <p><?php echo $news['details']; ?></p>
                </div>
            </a>
        <?php } ?>
    </div>
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

    <?php
    $conn->close();
    ?>
</body>
</html>
