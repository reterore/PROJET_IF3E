<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body>
<?php
session_start();
include('header.php');
$id_merchant = $_SESSION['id_merchant'];
?>
<main class="container">
    <article class="grid-fluid">
        <hgroup>
            <h1>Buy a new spaceship!</h1>
            <h2>Open yourself to a new range of mission</h2>
        </hgroup>
        <section class="grid">
            <div>
                <?php

                $reqCrédits = $db->prepare("SELECT intergalactic_credits FROM merchant
                                                    WHERE id_merchant = :id_merchant");
                $reqCrédits->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $reqCrédits->execute();
                $dataReq1 = $reqCrédits->fetch();
                $merchant_credits = $dataReq1[0];

                $query = $db->prepare("SELECT name, crew_capacity, cargo_capacity_ton, max_travel_range_parsec, price, id_spaceship, image 
                                            FROM spaceship");
                $query->execute();
                $info = $query->fetch();
                if ($query->rowCount() > 0) {
                    echo "<table border='1'>
        <tr>
            <th></th>
            <th>Model</th>
            <th>Crew capacity (pers.)</th>
            <th>Cargo capacity (ton)</th>
            <th>Max range (parsec)</th>
            <th>Acquisition Price</th>
            <th>Buy</th>
        </tr>";

                    while ($info != null) {
                        echo "<tr>";
                        echo "<td><img src='" . $info[6] . "' alt='no image'></td>"; // Affichage de l'image
                        echo "<td>" . $info[0] . "</td>";
                        echo "<td>" . $info[1] . "</td>";
                        echo "<td>" . $info[2] . "</td>";
                        echo "<td>" . $info[3] . "</td>";
                        echo "<td>" . $info[4] . " ¢</td>";

                        // Vérifiez si le marchand a assez d'argent pour acheter le vaisseau
                        if ($info[4] <= $merchant_credits) {
                            echo "<td><a href='confirm_spaceship.php?id_spaceship=" . $info[5] . "'>Buy</a></td>";
                        } else {
                            echo "<td>Not enough credits</td>";
                        }

                        echo "</tr>";
                        $info = $query->fetch();
                    }

                    echo "</table>";
                } else {
                    echo "Plus aucune mission, revenez plus tard.";
                }
                ?>
            </div>
        </section>
    </article>
</main>
</body>
</html>





