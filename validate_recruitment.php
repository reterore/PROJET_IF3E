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
                $id_crew_member = $_GET['id_crew_member'];
                $recruitmentPrice = $_GET['recruitment_price'];
                $db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");
                $id_merchant = $_SESSION['id_merchant'];

                // Récupérer les fonds actuels du marchand
                $fundsQuery = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                $fundsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_STR);
                $fundsQuery->execute();
                $currentFunds = $fundsQuery->fetchColumn();

                // Calculer les nouveaux fonds après le recrutement
                $newFunds = $currentFunds - $recruitmentPrice;

                // Mettre à jour les fonds du marchand
                $updateFundsQuery = $db->prepare("UPDATE merchant SET intergalactic_credits = :new_funds WHERE id_merchant = :id_merchant");
                $updateFundsQuery->bindParam(':new_funds', $newFunds, PDO::PARAM_INT);
                $updateFundsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_STR);
                $updateFundsQuery->execute();

                // Ajouter le membre d'équipage recruté dans la table "merchant_crew"
                $addCrewMember = $db->prepare("INSERT INTO merchant_crew(id_merchant, id_crew_member) VALUES(:id_merchant, :id_crew_member)");
                $addCrewMember->bindParam(':id_merchant', $id_merchant);
                $addCrewMember->bindParam(':id_crew_member', $id_crew_member);
                $addCrewMember->execute();

                $updateFundsQuery = $db->prepare("UPDATE crew_member SET in_a_team = 1 WHERE id_crew_member = :id_crew_member");
                $updateFundsQuery->bindParam(':id_crew_member', $id_crew_member, PDO::PARAM_STR);
                $updateFundsQuery->execute();

                echo "<h2>Recruitment Successful</h2>";
                echo "<p>The crew member has joined your team.</p>";
                echo "<br><div><a href='recruit_crew.php' role='btn' class='btn secondary'>Back to Crew Recruitment</a></div>";
                ?>
                <br>
            </div>
        </section>
    </article>
</main>
</body>
</html>
