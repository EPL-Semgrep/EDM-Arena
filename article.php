<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>EDM Article Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
<header class="bg-gray-800 p-4 flex justify-between items-center">
    <div class="text-2xl font-bold text-neon-green">
        <img alt="" class="inline-block mr-2" height="50" src="https://arvalen.github.io/Web/img/va.png" width="50"/>
        EDM Arena
    </div>
    <nav>
        <ul class="flex space-x-4">
            <li>
                <a class="hover:text-neon-green" href="community.php">Home</a>
            </li>
        </ul>
    </nav>
</header>
<main class="max-w-4xl mx-auto p-4">
    <article>
        <h1 class="text-4xl font-bold text-center text-neon-pink mt-8">Favorite EDM Tracks of 2024</h1>
        <div class="text-center text-gray-400 mt-2">
            <span>By Jane Doe</span> |
            <span>Published on October 10, 2024</span> |
            <span class="text-neon-green">EDM</span>,
            <span class="text-neon-green">Music Review</span>,
            <span class="text-neon-green">Artists</span>,
            <span class="text-neon-green">Festivals</span>
        </div>
        <div class="mt-8">
            <img alt="" class="w-full rounded-lg" height="400" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" width="800"/>
            <p class="text-center text-gray-400 mt-2">A breathtaking view of an EDM festival with electrifying lights and an energetic crowd.</p>
        </div>
        <div class="mt-8 space-y-4">
            <p>Share your favorite tracks of this year and discover new music from other members. The EDM scene in 2024 has been nothing short of spectacular, with artists pushing the boundaries of sound and performance.</p>
            <h2 class="text-2xl font-bold text-neon-blue">Top Tracks of 2024</h2>
            <p>From chart-topping hits to underground anthems, 2024 has been a year of incredible music. Here are some of the tracks that have defined the year:</p>
            <ul class="list-disc list-inside">
                <li><strong>Track 1:</strong> "Electric Dreams" by DJ Pulse</li>
                <li><strong>Track 2:</strong> "Neon Nights" by Synthwave</li>
                <li><strong>Track 3:</strong> "Bassline Boom" by Beatmaster</li>
                <li><strong>Track 4:</strong> "Rave Revolution" by Electro King</li>
                <li><strong>Track 5:</strong> "Dancefloor Frenzy" by Party Starter</li>
            </ul>
            <h2 class="text-2xl font-bold text-neon-blue">Discover New Music</h2>
            <p>The EDM community is always buzzing with new releases and hidden gems. Check out these tracks recommended by our members:</p>
            <ul class="list-disc list-inside">
                <li><strong>Track 6:</strong> "Midnight Groove" by Night Owl</li>
                <li><strong>Track 7:</strong> "Synth Symphony" by Melody Maker</li>
                <li><strong>Track 8:</strong> "Bass Drop" by Subwoofer</ li>
                <li><strong>Track 9:</strong> "Electric Avenue" by Voltage</li>
                <li><strong>Track 10:</strong> "Rave On" by Party People</li>
            </ul>
            <div class="mt-4">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/tcHJodG5hX8?si=DeMbKbQluvVBYk7G&amp;controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>            </div>
        </div>
    </article>
    <div class="mt-8 flex justify-center space-x-4">
        <a class="text-blue-500" href="#"><i class="fab fa-facebook fa-2x"></i></a>
        <a class="text-blue-400" href="#"><i class="fab fa-twitter fa-2x"></i></a>
        <a class="text-pink-500" href="#"><i class="fab fa-instagram fa-2x"></i></a>
        <a class="text-green-500" href="#"><i class="fab fa-spotify fa-2x"></i></a>
    </div>
    <section class="mt-8">
        <h2 class="text-2xl font-bold text-neon-pink">Comments</h2>
        <div class="mt-4 space-y-4">
            <div class="bg-gray-800 p-4 rounded-lg">
                <p class="font-bold">John Smith</p>
                <p class="text-gray-400">October 11, 2024</p>
                <p>Amazing article! EDM has truly transformed the music scene.</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg">
                <p class="font-bold">Emily Johnson</p>
                <p class="text-gray-400">October 12, 2024</p>
                <p>Can't wait for the next festival season. The energy is unmatched!</p>
            </div>
        </div>
        <div class="mt-4">
            <textarea class="w-full p-4 bg-gray-800 rounded-lg text-white" placeholder="Leave a comment..." rows="4"></textarea>
            <button class="mt-2 bg-neon-pink text-white px-4 py-2 rounded-lg">Submit</button>
        </div>
    </section>
    <section class="mt-8">
        <h2 class="text-2xl font-bold text-neon-pink">Related Articles</h2>
        <div class="mt-4 space-y-4">
            <div class="bg-gray-800 p-4 rounded-lg flex items-center">
                <img alt="Album cover of a popular EDM artist" class="w-24 h-24 rounded-lg mr-4" height="100" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" width="100"/>
                <div>
                    <h3 class="font-bold text-neon-blue">Top 10 EDM Albums of 2024</h3>
                    <p class="text-gray-400">A rundown of the best EDM albums released this year.</p>
                </div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg flex items-center">
                <img alt="Profile picture of a famous EDM artist" class="w-24 h-24 rounded-lg mr-4" height="100" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" width="100"/>
                <div>
                    <h3 class="font-bold text-neon-blue">Interview with Martin Garrix</h3>
                    <p class="text-gray-400">An exclusive interview with one of the biggest names in EDM.</p>
                </div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg flex items-center">
                <img alt="Scene from a major EDM festival" class="w-24 h-24 rounded-lg mr-4" height="100" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" width="100"/>
                <div>
                    < h3 class="font-bold text-neon-blue">Electric Daisy Carnival 2024 Preview</h3>
                    <p class="text-gray-400">What to expect from this year's EDC.</p>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>