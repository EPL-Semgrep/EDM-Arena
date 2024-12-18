<?php
include 'spotify_token.php';

$djs = [
    "Martin Garrix",
    "Matisse & Sadko",
    "Third Party",
    "David Guetta",
    // "Armin Van Buuren",
    // "Afrojack",
    "Alan Walker",
    // "KSHMR",
    // "R3hab",
    // "Lost Frequencies",
    // "W&W",
    // "Calvin Harris",
    // "Tiësto",
    "Nicky Romero",
    // "DJ Snake",
    // "Marshmello",
    // "Swedish House Mafia",
    // "The Chainsmokers",
    "Alesso",
    // "Nervo",
    "Zedd",
    "Lucas & Steve",
    "Mike Williams",
    "DubVision",
    "Tungevaag",
];

function getNewReleases($djs) {
    $token = getSpotifyToken();
    if (!$token) {
        echo "Failed to get access token.";
        return [];
    }

    $newReleases = [];
    $releaseUrls = [];

    foreach ($djs as $dj) {
        $url = "https://api.spotify.com/v1/search?q=" . urlencode($dj) . "&type=artist";
        $headers = [
            "Authorization: Bearer $token"
        ];

        $context = stream_context_create([
            'http' => [
                'header' => implode("\r\n", $headers),
                'method' => 'GET',
                'ignore_errors' => true
            ]
        ]);

        $response = file_get_contents($url, false, $context);
        $responseData = json_decode($response, true);
        if (isset($responseData['artists']['items']) && count($responseData['artists']['items']) > 0) {
            $artistId = $responseData['artists']['items'][0]['id'];

            $releaseUrl = "https://api.spotify.com/v1/artists/$artistId/albums?include_groups=album,single&market=US&limit=50&offset=0";
            $releaseUrls[$dj] = $releaseUrl;
        }
    }

    foreach ($releaseUrls as $dj => $releaseUrl) {
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer $token",
                'method' => 'GET',
                'ignore_errors' => true
            ]
        ]);

        $releaseResponse = file_get_contents($releaseUrl, false, $context);
        $releaseData = json_decode($releaseResponse, true);
        if (isset($releaseData['items'])) {
            foreach ($releaseData['items'] as $item) {
                $releaseDate = $item['release_date'];
                $currentYear = date('Y');
                if (strpos($releaseDate, $currentYear) !== false || strpos($releaseDate, $currentYear - 1) !== false) {
                    $newReleases[] = [
                        'artist' => $dj,
                        'album' => $item['name'],
                        'release_date' => $releaseDate,
                        'url' => $item['external_urls']['spotify'],
                        'cover' => $item['images'][0]['url'] 
                    ];
                }
            }
        }
    }

    usort($newReleases, function($a, $b) {
        return strtotime($b['release_date']) - strtotime($a['release_date']);
    });

    return array_slice($newReleases, 0, 80);
}

$newReleases = getNewReleases($djs);

$outputFormat = isset($_GET['format']) ? $_GET['format'] : 'html';

if ($outputFormat === 'text') {
    if (!empty($newReleases)) {
        foreach ($newReleases as $release) {
            echo "Artist: " . $release['artist'] . "\n";
            echo "Album: " . $release['album'] . "\n";
            echo "Release Date: " . $release['release_date'] . "\n";
            echo "Listen here: " . $release['url'] . "\n\n";
        }
    } else {
        echo "No new releases found.";
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Latest Music - EDM Arena</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="css/newmusic.css">
        <link href="asset/logo.svg" rel="shortcut icon" type="image/x-icon"/>

    </head>
    <body>
    <div class="navbar">
        <div class="logo">
            <img src="asset/logo.svg" alt="Logo">
            <span>EDM Arena</span>
        </div>
        <div class="nav-links">
            <a href="newmusic.php">NEW MUSIC</a>
            <a href="index.php#upcoming-festivals">UPCOMING FESTIVALS & EVENTS</a>
            <a href="index.php#latest-news">LATEST NEWS</a>
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
    <div class="latest-music" id="latest-music">
    <h2>Latest Music Releases</h2>
    <div class="music-cards">
        <?php if (!empty($newReleases)): ?>
            <?php foreach ($newReleases as $release): ?>
            <div class="music-card">
                <img src="<?php echo $release['cover']; ?>" alt="<?php echo $release['album']; ?> Cover" class="cover-image"> 
                <h3><?php echo $release['album']; ?></h3>
                <p><?php echo $release['artist']; ?></p>
                <p>Release Date: <?php echo $release['release_date']; ?></p>
                <a href="<?php echo $release['url']; ?>" class="listen-button" target="_blank">Listen</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No new releases found.</p>
        <?php endif; ?>
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
            <a href="#">Privacy Policy </a>
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

    </body>
    </html>
    <?php
}
?>