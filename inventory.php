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
    <article class="grid">
        <div>
            <?php
            $db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");

            // Vérifier si l'utilisateur est connecté
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
                $crewMembers = $crewMembersQuery->fetchAll(); // Utilisez fetchAll() pour obtenir tous les résultats

                echo "<h2>Crew Members List</h2>";
                echo "<ul>";
                foreach ($crewMembers as $crewMember) {
                    echo "<li>{$crewMember['first_name']} {$crewMember['last_name']} ({$crewMember['name']})</li>";
                }
            } else {
                echo "You need to be logged in to view crew member data.";
            }
            ?>
        </div>
        <div>
            <?php
            $db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");
            if (isset($_SESSION['id_merchant'])) {
                $spaceMerchantId = $_SESSION['id_merchant'];
                $spaceshipsQuery = $db->prepare("SELECT s.name, s.crew_capacity, s.cargo_capacity_kg, s.max_travel_range_parsec
                                FROM spaceship s
                                INNER JOIN merchant_spaceship ms ON s.id_spaceship = ms.id_spaceship
                                INNER JOIN merchant m ON m.id_merchant = ms.id_merchant
                                WHERE m.id_merchant = :id_merchant");
                $spaceshipsQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                $spaceshipsQuery->execute();
                $spaceships = $spaceshipsQuery->fetch(); // Utilisez fetchAll() pour obtenir tous les résultats

                echo "<h2>Spaceships list</h2>";
                if ($spaceships != null) {
                    // Afficher les données des vaisseaux spatiaux
                    while ($spaceships != null) {
                        echo "<details>";
                        echo "<summary>{$spaceships['name']}</summary>";
                        echo "<li><strong>Crew Capacity:</strong> {$spaceships['crew_capacity']}</li>";
                        echo "<li><strong>Cargo Capacity:</strong> {$spaceships['cargo_capacity_kg']} kg</li>";
                        echo "<li><strong>Maximum Travel Range in Parsecs:</strong> {$spaceships['max_travel_range_parsec']}</li>";
                        echo "</details>";
                        $spaceships = $spaceshipsQuery->fetch();
                    }
                    echo"</details>";
                } else {
                    echo "Aucun vaisseau spatial trouvé pour ce marchand.";
                }
            } else {
                echo "Vous devez être connecté pour voir les données des vaisseaux spatiaux.";
            }
            ?>
        </div>
    </article>
</main>
</body>
</html>


