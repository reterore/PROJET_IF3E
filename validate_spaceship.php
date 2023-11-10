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
                $id_spaceship = $_GET['id_spaceship'];
                $purchasePrice = $_GET['price'];
                $id_merchant = $_SESSION['id_merchant'];

                // Récupérer les fonds actuels du marchand
                $fundsQuery = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                $fundsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_STR);
                $fundsQuery->execute();
                $currentFunds = $fundsQuery->fetchColumn();

                // Vérifier si les fonds sont suffisants pour acheter le vaisseau
                if ($currentFunds >= $purchasePrice) {
                    // Calculer les nouveaux fonds après l'achat
                    $newFunds = $currentFunds - $purchasePrice;

                    // Mettre à jour les fonds du marchand
                    $updateFundsQuery = $db->prepare("UPDATE merchant SET intergalactic_credits = :new_funds WHERE id_merchant = :id_merchant");
                    $updateFundsQuery->bindParam(':new_funds', $newFunds, PDO::PARAM_INT);
                    $updateFundsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_STR);
                    $updateFundsQuery->execute();

                    // Ajouter le vaisseau spatial acheté dans la table "merchant_spaceships"
                    $addSpaceship = $db->prepare("INSERT INTO merchant_spaceship(id_merchant, id_spaceship) VALUES(:id_merchant, :id_spaceship)");
                    $addSpaceship->bindParam(':id_merchant', $id_merchant);
                    $addSpaceship->bindParam(':id_spaceship', $id_spaceship);
                    $addSpaceship->execute();

                    echo "<h2>Purchase Successful</h2>";
                    echo "<p>The spaceship is now part of your fleet.</p>";
                } else {
                    echo "<h2>Purchase Failed</h2>";
                    echo "<p>You do not have enough credits to purchase this spaceship.</p>";
                }

                echo "<br><div><a href='spaceship.php' role='btn' class='btn secondary'>Back to Spaceships</a></div>";
                ?>
                <br>
            </div>
        </section>
    </article>
</main>
</body>
</html>
