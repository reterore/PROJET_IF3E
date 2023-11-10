<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Our Project</title>
</head>
<body>
<?php
$db = new PDO("mysql:host=localhost; dbname=space_merchant; charset=utf8", "sa", "rasta");
$query = $db->prepare("SELECT name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, image FROM spaceship WHERE name = 'infinity traveler';");
$query->execute();
$info = $query->fetch();
$imageURL = $info[5]; // L'URL de l'image

// Assurez-vous que l'image existe avant de l'afficher
?>


<main class="container">
    <article class="grid-fluid">
            <a href="login.php" class="secondary">Back to Main Page</a>
        <br>
        <section class="grid">
            <div>
                <h1>Our Project: Galactic Commerce Management System</h1>
            </div>
        </section>
        <section class="grid">
            <div>
                <p>In the ever-expanding frontiers of space commerce, the need for efficient and streamlined management tools has led to the development of the Galactic Commerce Management System. This system acts as a bridge between space traders and their dynamic business activities across the galaxy.</p>
                <p>Designed with the essence of accessibility, interactivity, and versatility, this web platform allows users to oversee and optimize their interstellar business operations seamlessly. From creating missions to acquiring spacecraft, recruiting crews, the Galactic Commerce Management System heralds a new era of data-driven entrepreneurship beyond the bounds of our planetary world.</p>
                <p>Explore missions, manage your fleet, recruit and assign crew members, and even create your own missions. Our robust database architecture ensures data integrity and real-time updates, allowing you to interact with your galactic business with ease.</p>
                <p>You will be able to create mission or to participate in other people mission in order to earn intergalactic credits. You will need to build your fleet and your crew to succeed in missions and be the best merchant of the universe.</p>
            </div>
        </section>
    </article>
</main>
</body>
</html>
