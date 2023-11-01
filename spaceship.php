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
            <hgroup>
                <h1>Buy a new spaceship!</h1>
                <h2>Open yourself to a new range of mission</h2>
            </hgroup>
            <section class="grid">
                <div>
                    <?php
                    // Votre code de connexion à la base de données

                    $query = $db->prepare("SELECT name, crew_capacity, cargo_capacity_kg, max_travel_range_parsec, price, id_spaceship FROM spaceship");
                    $query->execute();
                    $info = $query->fetch();
                    if ($query->rowCount() > 0) {
                        echo "<table border='1'>
        <tr>
            <th>Model</th>
            <th>Crew capacity (pers.)</th>
            <th>Cargo capacity (kg)</th>
            <th>Max range (parsec)</th>
            <th>Price</th>
            <th>Buy</th>
        </tr>";

                        while ($info != null) {
                            echo "<tr>";
                            echo "<td>" . $info[0] . "</td>";
                            echo "<td>" . $info[1] . "</td>";
                            echo "<td>" . $info[2] . "</td>";  // Ah ça il aime pas dis donc
                            echo "<td>" . $info[3] . "</td>";
                            echo "<td>" . $info[4] . " ¢</td>";
                            echo "<td><a href='confirm_spaceship.php?id_spaceship=" . $info[5] . "'>>>Buy<<</a></td>";
                            $info = $query->fetch();
                        }

                        echo "</table>";
                    } else {
                        echo "Plus aucune mission, revenez plus tard.";
                    }
                    ?>
            </section>
    </article>
</main>
</body>
</html>