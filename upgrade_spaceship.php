<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Login to Your Account</title>
</head>
<body>

<?php
session_start();
include('header.php');

$id_merchant = $_SESSION['id_merchant'];
$id_spaceship = $_GET['id_spaceship'];
$spaceshipsQuery = $db->prepare("SELECT s.name, s.crew_capacity, s.cargo_capacity_ton, s.max_travel_range_parsec, s.image, ms.level, s.id_spaceship, s.price
                                FROM spaceship s
                                INNER JOIN merchant_spaceship ms ON s.id_spaceship = ms.id_spaceship
                                INNER JOIN merchant m ON m.id_merchant = ms.id_merchant
                                WHERE m.id_merchant = :id_merchant
                                AND s.id_spaceship = :id_spaceship");
$spaceshipsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
$spaceshipsQuery->bindParam(':id_spaceship', $id_spaceship, PDO::PARAM_INT);

$spaceshipsQuery->execute();
$spaceships = $spaceshipsQuery->fetch();
$priceUpgrade = $spaceships['price'] * ($spaceships['level'] + 1);

$reqMerchant = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
$reqMerchant->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
$reqMerchant->execute();
$dataMerchant = $reqMerchant->fetch();
$merchantFunds = $dataMerchant['intergalactic_credits'];
?>

<main class="container">
    <article class="grid">
        <div>
            <?php
            if ($spaceships) {
                echo "<h1>Upgrade your Spaceship: {$spaceships['name']}";
                for ($i = 0; $i < $spaceships['level']; $i++) {
                    echo " ⛤";
                }
                echo "<img src='{$spaceships['image']}' alt='Spaceship Image'>";
                echo "</h1>";

                echo "<ul>";
                echo "<li><strong>Crew Capacity:</strong> {$spaceships['crew_capacity']}</li>";
                echo "<li><strong>Cargo Capacity:</strong> {$spaceships['cargo_capacity_ton']} tons</li>";

                $newRange = $spaceships['max_travel_range_parsec'] + ($spaceships['max_travel_range_parsec'] * 0.1 * ($spaceships['level']));
                echo "<li><strong>Maximum Travel Range in Parsecs:</strong> <span style='color: green;'>{$newRange} ↑</span> </li>";
                echo "</ul>";

                if ($spaceships['level'] < 3) {
                    echo "<p>Upgrading your spaceship allows you to go further without spending more on resources!</p>";

                    if ($merchantFunds >= $priceUpgrade) {
                        echo "<a href='validate_upgrade.php?id_spaceship={$spaceships['id_spaceship']}' role='button' class='btn'>Upgrade Spaceship for $priceUpgrade ¢</a>";
                    } else {
                        echo "<p>You don't have enough funds to upgrade your spaceship ($priceUpgrade ¢) </p>";
                    }
                } else {
                    echo "<p>Your Spaceship is at the maximum level.</p>";
                }
            } else {
                echo "<p>Spaceship not found.</p>";
            }
            ?>
        </div>
    </article>
</main>

</body>
</html>
