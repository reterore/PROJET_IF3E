<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Mission Result</title>
</head>
<body>

<?php
session_start();
include('header.php');
?>

<main class="container">
    <article class="grid">
        <div class="col-6 centered">
            <?php
            $id_mission = $_POST['id_mission'];
            $selectedSpaceship = $_POST['selected_spaceship'];
            $crewMembers = $_POST['selected_crew_members'];
            $id_merchant = $_SESSION['id_merchant'];
            $doerNewFunds = 0;
            $changeDoerFunds = "";

            // Réinitialisez les valeurs POST
            $_POST = array();

            // Vérifiez si la mission est déjà "done"
            $reqCheckMission = $db->prepare("SELECT done FROM mission WHERE id_mission = :id_mission");
            $reqCheckMission->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
            $reqCheckMission->execute();
            $missionStatus = $reqCheckMission->fetchColumn();

            if ($missionStatus == 1) {
                // Si la mission est déjà "done", affichez un message d'erreur et empêchez le traitement
                echo "<h1>Mission Already Completed</h1>";
                echo "<p>This mission has already been completed and cannot be repeated.</p>";
                echo "<a href='home.php' class='secondary'>Go back to mission</a>";
            } else {
                // Si la mission n'a pas encore été "done", continuez le traitement
                $reqMerchant = $db->prepare("SELECT * FROM merchant WHERE id_merchant = :id_merchant;");
                $reqMerchant->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $reqMerchant->execute();
                $dataMerchant = $reqMerchant->fetch();
                $creditsMerchant = $dataMerchant['intergalactic_credits'];

                $reqDistance = $db->prepare("SELECT p.distance_from_earth, a.name, m.reward FROM planet p
                    INNER JOIN mission m ON m.id_planet = p.id_planet
                    INNER JOIN ability a ON m.id_ability = a.id_ability
                    WHERE id_mission = :id_mission");
                $reqDistance->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
                $reqDistance->execute();
                $distanceAbility = $reqDistance->fetch();
                $distance = $distanceAbility[0];
                $ability_mission = $distanceAbility[1];
                $reward = $distanceAbility[2];

                $reqCapacity = $db->prepare("SELECT cargo_capacity_ton FROM spaceship WHERE name = :selectedSpaceship");
                $reqCapacity->bindParam(':selectedSpaceship', $selectedSpaceship, PDO::PARAM_STR);
                $reqCapacity->execute();
                $dataCapacity = $reqCapacity->fetch();
                $capacity = $dataCapacity[0];
                $reqPrice = $db->prepare("SELECT price_by_ton, id_merchant FROM cargo_type ct
                        INNER JOIN mission m ON m.id_cargo_type = ct.id_cargo_type
                        WHERE id_mission = :id_mission");
                $reqPrice->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
                $reqPrice->execute();
                $dataMission = $reqPrice->fetch();
                $priceTon = $dataMission[0];
                $id_mission_merchant = $dataMission[1];

                $reqCreditsRequester = $db->prepare("SELECT intergalactic_credits, first_name, last_name FROM merchant WHERE id_merchant = :id_merchant");
                $reqCreditsRequester->bindParam(':id_merchant', $id_mission_merchant, PDO::PARAM_INT);
                $reqCreditsRequester->execute();
                $dataCreditsRequester = $reqCreditsRequester->fetch();
                $CreditsRequester = $dataCreditsRequester[0];
                $fnameRequester = $dataCreditsRequester[1];
                $lnameRequester = $dataCreditsRequester[2];
                $MissionGain = $priceTon * $capacity;
                $NewFundsRequester = $CreditsRequester + $MissionGain;

                // Utilisez une variable pour compter le nombre total d'occurrences
                $totalOccurrences = 0;

                // Utilisez une variable pour suivre les chances de réussite de la mission (55% de base)
                $successProbability = 55;

                // Utilisez une boucle foreach pour afficher chaque membre de l'équipage
                foreach ($crewMembers as $crewMember) {
                    // Utilisez substr_count pour compter le nombre d'occurrences de $ability_mission dans $crewMember
                    $occurrences = substr_count($crewMember, $ability_mission);
                    if ($occurrences > 0) {
                        $totalOccurrences += $occurrences;
                        // Augmentez les chances de réussite de 15% pour chaque membre ayant l'ability de la mission
                        $successProbability += 15;
                    }
                }

                // Affichez les chances de réussite de la mission
                echo "<script>alert('Chances of Succeeding The Mission : $successProbability%');</script>";

                echo "<br>";

                $random = rand(0, 100);
                $missionSuccess = ($random <= $successProbability) ? 1 : 0;

                if ($missionSuccess == 1) {
                    $doerNewFunds = $creditsMerchant + $reward;
                    $changeRequesterFunds = $db->prepare("UPDATE merchant SET intergalactic_credits = :newFunds WHERE id_merchant = :id_merchant");
                    $changeRequesterFunds->bindParam(':id_merchant', $id_mission_merchant, PDO::PARAM_INT);
                    $changeRequesterFunds->bindParam(':newFunds', $NewFundsRequester, PDO::PARAM_INT);
                    $changeRequesterFunds->execute();

                    $reqMissionDone = $db->prepare("UPDATE mission SET done = 1 WHERE id_mission = :id_mission");
                    $reqMissionDone->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
                    $reqMissionDone->execute();
                } else {
                    $doerNewFunds = $creditsMerchant - $distance;
                }

                // Mettez à jour les fonds du marchand
                $changeDoerFunds = $db->prepare("UPDATE merchant SET intergalactic_credits = :newFunds WHERE id_merchant = :id_merchant");
                $changeDoerFunds->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $changeDoerFunds->bindParam(':newFunds', $doerNewFunds, PDO::PARAM_INT);
                $changeDoerFunds->execute();


                echo "<h1>Your Mission " . ($missionSuccess ? "Succeed!" : "Failed...") . "</h1>";
                echo "<p>You " . ($missionSuccess ? "won $reward ¢ and $fnameRequester $lnameRequester won $MissionGain ¢" : "lost $distance ¢") . "</p>";
                echo "<a href='home.php' class='secondary'>Go back to mission</a>";
               }
            ?>
        </div>
    </article>
</main>
</body>
</html>