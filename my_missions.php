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
?>

<main class="container">

    <section class="grid">
        <div>
            <?php
            echo "<h2>Here's your Mission</h2>";
            echo "<p>You can consult your mission anytime</p>";
            $query = $db->prepare("SELECT mission.name, cargo_type.type, planet.name, ability.name, reward, mission.id_mission, done
                                  FROM mission 
                                  JOIN cargo_type ON mission.id_cargo_type = cargo_type.id_cargo_type
                                  JOIN planet ON mission.id_planet = planet.id_planet 
                                  JOIN ability ON mission.id_ability = ability.id_ability
                                  AND mission.done = 0
                                  AND mission.id_merchant = :id_merchant  ;");
            $query->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
            $query->execute();

            if ($query->rowCount() > 0) {
                echo "<table border='1'>
                    <tr>
                        <th>Mission</th>
                        <th>Cargo</th>
                        <th>Planet</th>
                        <th>Ability</th>
                        <th>Reward</th>
                        <th>Done</th>
                        <th>Modify</th>
                    </tr>";
                while ($affichage = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>" . $affichage[0] . "</td>";
                    echo "<td>" . $affichage[1] . "</td>";
                    echo "<td>" . $affichage[2] . "</td>";
                    echo "<td>" . $affichage[3] . "</td>";
                    echo "<td>" . $affichage[4] . " ¢</td>";
                    if ($affichage[6] == 0){
                        echo "<td> NO </td>";
                    }else{
                        echo "<td> YES </td>";

                    }
                    if ($affichage[6] == 0){
                        echo "<td><a href='modify_mission.php?id_mission=" . $affichage[5] . "'>>>modify<<</a></td>";
                    }
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "You did not create any mission.";
            }
            ?>
        </div>
    </section>
</main>
</body>
</html>


