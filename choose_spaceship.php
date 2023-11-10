<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .col-4 {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px;
        }
        .col-4 h4 {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .col-4 ul {
            list-style-type: disc;
            padding-left: 20px;
        }
    </style>
    <script>
        function validateSpaceshipSelection() {
            var selectedSpaceship = document.getElementById("selected_spaceship").value;
            if (selectedSpaceship === "") {
                alert("Please select a spaceship before proceeding.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php
session_start();
include('header.php');
?>
<main class="container">
    <article class="grid">
        <div class="col-4">
            <br>
            <?php
            if (isset($_GET['id_mission'])) {
                $id_mission = $_GET['id_mission'];

                $query = $db->prepare("SELECT mission.name, cargo_type.type, planet.name, ability.name, reward, mission.description, planet.distance_from_earth
                                        FROM mission 
                                        JOIN cargo_type ON mission.id_cargo_type = cargo_type.id_cargo_type
                                        JOIN planet ON mission.id_planet = planet.id_planet 
                                        JOIN ability ON mission.id_ability = ability.id_ability
                                        WHERE id_mission = :id_mission");
                $query->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
                $query->execute();
                $mission = $query->fetch();
                if ($mission != false) {
                    echo "<h1>Summary of Mission:</h1>";
                    echo "<p>" . $mission[0] . "</p>";
                    echo "<p><strong>Cargo Type:</strong> " . $mission[1] . "</p>";
                    echo "<p><strong>Planet:</strong> " . $mission[2] . "</p>";
                    echo "<p><strong>Useful Ability:</strong> " . $mission[3] . "</p>";
                    echo "<p><strong>Mission Reward:</strong> " . $mission[4] . " ¢</p>";
                    echo "<p><strong>Description:</strong> " . $mission[5] . "</p>";
                    echo "<a href='home.php' role='button' class='btn secondary'>Cancel this Mission</a>";
                } else {
                    echo "Mission not found.";
                }
            } else {
                echo "Mission ID not provided.";
            }
            ?>
        </div>
        <div class="col-4">
            <?php
            if (isset($_SESSION['id_merchant']) && isset($_GET['id_mission'])) {
                $spaceMerchantId = $_SESSION['id_merchant'];
                $missionId = $_GET['id_mission'];

                $missionRangeQuery = $db->prepare("SELECT p.distance_from_earth
        FROM mission m
        INNER JOIN planet p ON m.id_planet = p.id_planet
        WHERE m.id_mission = :mission_id");
                $missionRangeQuery->bindParam(':mission_id', $missionId, PDO::PARAM_INT);
                $missionRangeQuery->execute();
                $dataMissionRange = $missionRangeQuery->fetch();
                $missionRange = $dataMissionRange[0];
                $NewMissionRange =

                $spaceshipsQuery = $db->prepare("SELECT DISTINCT s.name, s.crew_capacity, s.cargo_capacity_ton, s.max_travel_range_parsec, ms.level
    FROM spaceship s
    INNER JOIN merchant_spaceship ms ON s.id_spaceship = ms.id_spaceship
    INNER JOIN merchant m ON m.id_merchant = ms.id_merchant
    WHERE m.id_merchant = :id_merchant
    AND (s.max_travel_range_parsec + (s.max_travel_range_parsec * 0.1 * ms.level)) >= :mission_range");

                $spaceshipsQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                $spaceshipsQuery->bindParam(':mission_range', $missionRange, PDO::PARAM_INT);
                $spaceshipsQuery->execute();
                $spaceships = $spaceshipsQuery->fetchAll();



                echo "<h4>Spaceships Available for the Mission</h4>";
                if (!empty($spaceships)) {
                    echo "<ul>";
                    foreach ($spaceships as $spacecraft) {
                        echo "<li><details>";
                        echo "<summary>{$spacecraft['name']}";
                        for ($i = 0; $i < $spacecraft['level']; $i++) {
                            echo " ⛤";
                        }
                        echo "</summary>";
                        echo "<ul>";
                        echo "<li><strong>Crew Capacity:</strong> {$spacecraft['crew_capacity']}</li>";
                        echo "<li><strong>Cargo Capacity:</strong> {$spacecraft['cargo_capacity_ton']} kg</li>";

                        // Calculer la nouvelle portée en fonction du niveau du vaisseau
                        $newRange = $spacecraft['max_travel_range_parsec'] + ($spacecraft['max_travel_range_parsec'] * 0.1 * ($spacecraft['level']));
                        echo "<li><strong>Maximum Travel Range in Parsecs:</strong> {$newRange}</li>";
                        $newPlanetRange = $mission['planet'];
                        echo "</ul>";
                        echo "</details></li>";
                    }
                    echo "</ul>";
                    echo "<a href='spaceship.php' role='button' class='btn secondary'>Buy New Spaceship</a>";
                } else {
                    echo "No spaceship found with sufficient travel range for this mission ($missionRange parsec).";
                    echo "<br>";
                    echo "<a href='spaceship.php' role='button' class='btn primary'>Buy One</a>";
                }
            } else {
                echo "You must be logged in to view spaceship data.";
            }
            ?>

        </div>
        <div class="col-4">
            <form method="post" action="choose_crew_members.php" onsubmit="return validateSpaceshipSelection();">
                <h1><label for="selected_spaceship">Select a Spaceship:</label></h1>
                <select name="selected_spaceship" id="selected_spaceship">
                    <?php
                    if (isset($_SESSION['id_merchant'])) {
                        $spaceMerchantId = $_SESSION['id_merchant'];
                        $id_mission = $_GET['id_mission'];

                        $missionRangeQuery = $db->prepare("SELECT p.distance_from_earth
                    FROM mission m
                    INNER JOIN planet p ON m.id_planet = p.id_planet
                    WHERE m.id_mission = :id_mission");
                        $missionRangeQuery->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
                        $missionRangeQuery->execute();
                        $dataMissionRange = $missionRangeQuery->fetch();
                        $NewMissionRange = $dataMissionRange[0];

                        $spaceshipsQuery = $db->prepare("SELECT DISTINCT s.name, s.crew_capacity, s.cargo_capacity_ton, s.max_travel_range_parsec, ms.level
                    FROM spaceship s
                    INNER JOIN merchant_spaceship ms ON s.id_spaceship = ms.id_spaceship
                    INNER JOIN merchant m ON m.id_merchant = ms.id_merchant
                    WHERE m.id_merchant = :id_merchant
                    AND (s.max_travel_range_parsec + (s.max_travel_range_parsec * 0.1 * ms.level)) >= :mission_range");

                        $spaceshipsQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                        $spaceshipsQuery->bindParam(':mission_range', $NewMissionRange, PDO::PARAM_INT);
                        $spaceshipsQuery->execute();

                        while ($spacecraft = $spaceshipsQuery->fetch(PDO::FETCH_ASSOC)) {
                            $maxTravelRange = $spacecraft['max_travel_range_parsec'] + ($spacecraft['max_travel_range_parsec'] * 0.1 * $spacecraft['level']);
                            $remainingRange = $maxTravelRange - $NewMissionRange;

                            if ($remainingRange >= 0) {
                                echo "<option value='" . $spacecraft['name'] . "'>";
                                echo $spacecraft['name'];
                                for ($i = 0; $i < $spacecraft['level']; $i++) {
                                    echo " ⛤";
                                }
                                echo "</option>";
                            }
                        }
                    }
                    ?>
                </select>
                <input type='hidden' name='id_mission' value='<?php echo $id_mission; ?>'>
                <input type="submit" value="Select">
            </form>
        </div>
    </article>
</main>
</body>
</html>
