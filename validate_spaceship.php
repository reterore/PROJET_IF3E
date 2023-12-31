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

                $checkOwnership = $db->prepare("SELECT COUNT(*) FROM merchant_spaceship WHERE id_merchant = :id_merchant AND id_spaceship = :id_spaceship");
                $checkOwnership->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $checkOwnership->bindParam(':id_spaceship', $id_spaceship, PDO::PARAM_INT);
                $checkOwnership->execute();
                $ownershipCount = $checkOwnership->fetchColumn();

                if ($ownershipCount > 0) {
                    echo "<h2>Already Owned</h2>";
                    echo "<p>You already own this model of spaceship.</p>";
                } else {
                    $fundsQuery = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                    $fundsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                    $fundsQuery->execute();
                    $currentFunds = $fundsQuery->fetchColumn();

                    if ($currentFunds >= $purchasePrice) {
                        $newFunds = $currentFunds - $purchasePrice;

                        $updateFundsQuery = $db->prepare("UPDATE merchant SET intergalactic_credits = :new_funds WHERE id_merchant = :id_merchant");
                        $updateFundsQuery->bindParam(':new_funds', $newFunds, PDO::PARAM_INT);
                        $updateFundsQuery->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                        $updateFundsQuery->execute();

                        $addSpaceship = $db->prepare("INSERT INTO merchant_spaceship(id_merchant, id_spaceship) VALUES(:id_merchant, :id_spaceship)");
                        $addSpaceship->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                        $addSpaceship->bindParam(':id_spaceship', $id_spaceship, PDO::PARAM_INT);
                        $addSpaceship->execute();

                        echo "<h2>Purchase Successful</h2>";
                        echo "<p>The spaceship is now part of your fleet.</p>";
                    } else {
                        echo "<h2>Purchase Failed</h2>";
                        echo "<p>You do not have enough credits to purchase this spaceship.</p>";
                    }
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
