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
            <h1>Recruit Crew Members</h1>
            <h2>Build your crew for new missions</h2>
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

                $query = $db->prepare("SELECT first_name, last_name, id_ability, recruitment_price, id_crew_member FROM crew_member WHERE in_a_team = 0 ORDER BY recruitment_price");
                $query->execute();
                $info = $query->fetch();
                if ($query->rowCount() > 0) {
                    echo "<table border='1'>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Ability</th>
                            <th>Recruitment Price</th>
                            <th>Recruit</th>
                        </tr>";

                    while ($info != null) {
                        echo "<tr>";
                        echo "<td>" . $info[0] . "</td>";
                        echo "<td>" . $info[1] . "</td>";

                        // Récupérez le nom de l'ability à partir de la table d'abilities en utilisant id_ability
                        $abilityQuery = $db->prepare("SELECT name FROM ability WHERE id_ability = :id_ability");
                        $abilityQuery->bindParam(':id_ability', $info[2], PDO::PARAM_INT);
                        $abilityQuery->execute();
                        $ability = $abilityQuery->fetch();
                        echo "<td>" . $ability[0] . "</td>";
                        echo "<td>" . $info[3] . " ¢</td>";

                        // Vérifiez si le marchand a assez de crédits pour recruter
                        if ($info[3] <= $merchant_credits) {
                            echo "<td><a href='confirm_recruitment.php?id_crew_member=" . $info[4] . "&recruitment_price=" . $info[3] . "'>Recruit</a></td>";
                        } else {
                            echo "<td>Not enough credits</td>";
                        }

                        echo "</tr>";
                        $info = $query->fetch();
                    }

                    echo "</table>";
                } else {
                    echo "No crew members available for recruitment at the moment.";
                }
                ?>
            </div>
        </section>
    </article>
</main>
</body>
</html>
