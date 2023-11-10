<!DOCTYPE html>
<html lang="en">
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
    <article class="grid">
        <div>
            <?php
            if (isset($_SESSION['id_merchant'])) {
                $spaceMerchantId = $_SESSION['id_merchant'];
                $crewMembersQuery = $db->prepare("SELECT cm.first_name, cm.last_name, a.name
                                        FROM merchant m
                                        INNER JOIN merchant_crew mc ON m.id_merchant = mc.id_merchant
                                        INNER JOIN crew_member cm ON mc.id_crew_member = cm.id_crew_member
                                        INNER JOIN ability a ON cm.id_ability = a.id_ability
                                        WHERE m.id_merchant = :id_merchant");
                $crewMembersQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                $crewMembersQuery->execute();
                $crewMembers = $crewMembersQuery->fetchAll();

                echo "<h2>Crew Member List</h2>";
                echo "<ul>";
                if (!empty($crewMembers)) {
                    foreach ($crewMembers as $crewMember) {
                        echo "<li>{$crewMember['first_name']} {$crewMember['last_name']} ({$crewMember['name']})</li>";
                    }
                } else {
                    echo "<li>You don't have any crew members.</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>You need to be logged in to view crew member data.</p>";
            }
            ?>
        </div>

        <div>
            <?php
            if (isset($_SESSION['id_merchant'])) {
                $spaceMerchantId = $_SESSION['id_merchant'];
                $spaceshipsQuery = $db->prepare("SELECT s.name, s.crew_capacity, s.cargo_capacity_ton, s.max_travel_range_parsec, s.image, ms.level, ms.id_spaceship
                                FROM spaceship s
                                INNER JOIN merchant_spaceship ms ON s.id_spaceship = ms.id_spaceship
                                INNER JOIN merchant m ON m.id_merchant = ms.id_merchant
                                WHERE m.id_merchant = :id_merchant
                                ORDER BY max_travel_range_parsec");
                $spaceshipsQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                $spaceshipsQuery->execute();
                $spaceships = $spaceshipsQuery->fetchAll(); // Utilisez fetchAll() pour obtenir tous les résultats

                echo "<h2>Spaceships list</h2>";
                if (!empty($spaceships)) {
                    foreach ($spaceships as $spaceship) {
                        echo "<details>";
                        echo "<summary><img src='{$spaceship['image']}' alt='Image'> {$spaceship['name']}";

                        for ($i = 0; $i < $spaceship['level']; $i++) {
                            echo " ⛤";
                        }
                        echo "</summary>";

                        echo "<ul>";
                        echo "<li><strong>Crew Capacity:</strong> {$spaceship['crew_capacity']}</li>";
                        echo "<li><strong>Cargo Capacity:</strong> {$spaceship['cargo_capacity_ton']} tons</li>";

                        // Calculer la nouvelle portée en fonction du niveau du vaisseau
                        $newRange = $spaceship['max_travel_range_parsec'] + ($spaceship['max_travel_range_parsec'] * 0.1 * ($spaceship['level'] - 1));
                        echo "<li><strong>Maximum Travel Range in Parsecs:</strong> {$newRange}</li>";

                        echo "</ul>";

                        if ($spaceship['level'] < 3) {
                            $id_spaceship = $spaceship['id_spaceship'];
                            echo "<a href='upgrade_spaceship.php?id_spaceship={$id_spaceship}' class='btn'>-->Upgrade Spaceship<--</a>";
                        } else {
                            echo "<p>Your Spaceship is at the maximum level.</p>";
                        }

                        echo "</details>";
                    }
                } else {
                    echo "<p>You don't have any spaceships</p>";
                }
            } else {
                echo "<p>You need to be connected to your account</p>";
            }
            ?>
        </div>
    </article>
</main>
</body>
</html>
