<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body>
<?php
session_start();
include('header.php');
?>

<main class="container">
    <article class="grid-fluid">
        <section class="grid">
            <div>
                <?php

                if (isset($_GET['id_spaceship'])) {
                    $id_spaceship = $_GET['id_spaceship'];

                    $query = $db->prepare("SELECT name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price FROM spaceship WHERE id_spaceship = :id_spaceship ORDER BY max_travel_range_parsec");
                    $query->bindParam(':id_spaceship', $id_spaceship, PDO::PARAM_INT);
                    $query->execute();
                    $spaceship = $query->fetch();

                    if ($spaceship != null) {
                        $spaceMerchantId = $_SESSION['id_merchant'];
                        $spaceshipPrice = $spaceship['price'];

                        $checkFundsQuery = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                        $checkFundsQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_STR);
                        $checkFundsQuery->execute();
                        $spaceMerchantFunds = $checkFundsQuery->fetch();

                        echo "<h2>Spaceship Details</h2>";
                        echo "<p><strong>Name:</strong> " . $spaceship['name'] . "</p>";
                        echo "<p><strong>Crew Capacity:</strong> " . $spaceship['crew_capacity'] . "</p>";
                        echo "<p><strong>Cargo Capacity (kg):</strong> " . $spaceship['cargo_capacity_ton'] . "</p>";
                        echo "<p><strong>Max Travel Range (parsec):</strong> " . $spaceship['max_travel_range_parsec'] . "</p>";
                        echo "<p><strong>Price:</strong> " . $spaceship['price'] . " ¢</p>";

                        if ($spaceMerchantFunds[0] >= $spaceshipPrice) {
                            echo "<a href='spaceship.php' role='button' class='secondary'>Cancel purchase</a>";
                            echo " ";
                            echo "<a href='validate_spaceship.php?id_spaceship=$id_spaceship&price=" . $spaceship['price'] . "' role='button' class='btn primary'>Confirm purchase</a>";
                        } else {
                            echo "Insufficient funds. You cannot purchase the spaceship.</br></br>";
                            echo "<a href='spaceships.php' role='button' class='secondary'>Back to Spaceships</a>";
                            echo " ";
                            echo "<a href='home.php' role='button' >Go to missions</a>";
                        }
                    } else {
                        echo "Invalid spaceship selection.";
                    }
                } else {
                    echo "Spaceship not selected. Please go back to the spaceships page.";
                }
                ?>
                <br>
            </div>
        </section>
    </article>
</main>
</body>
</html>
