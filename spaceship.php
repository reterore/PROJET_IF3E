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
                $reqCredits = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                $reqCredits->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $reqCredits->execute();
                $dataReq1 = $reqCredits->fetch();
                $merchant_credits = $dataReq1[0];

                $query = $db->prepare("SELECT s.name, s.crew_capacity, s.cargo_capacity_ton, s.max_travel_range_parsec, s.price, s.id_spaceship, s.image
                                            FROM spaceship s
                                            LEFT JOIN merchant_spaceship ms ON s.id_spaceship = ms.id_spaceship AND ms.id_merchant = :id_merchant
                                            WHERE ms.id_merchant IS NULL");
                $query->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
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
                        echo "<td><img src='" . $info['image'] . "' alt='no image'></td>";
                        echo "<td>" . $info['name'];
                        echo " ⛤";
                        echo "</td>";
                        echo "<td>" . $info['crew_capacity'] . "</td>";
                        echo "<td>" . $info['cargo_capacity_ton'] . "</td>";
                        echo "<td>" . $info['max_travel_range_parsec'] . "</td>";
                        echo "<td>" . $info['price'] . " ¢</td>";

                        if ($info['price'] <= $merchant_credits) {
                            echo "<td><a href='confirm_spaceship.php?id_spaceship=" . $info['id_spaceship'] . "'>Buy</a></td>";
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
