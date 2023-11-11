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
                $id_merchant = $_SESSION['id_merchant'];
                $id_spaceship = $_GET['id_spaceship'];

                $levelUpgrade = $db->prepare("SELECT level , price
                                                        FROM spaceship s
                                                        INNER JOIN merchant_spaceship ms ON ms.id_spaceship = s.id_spaceship
                                                        WHERE id_merchant = :id_merchant
                                                        AND s.id_spaceship = :id_spaceship");
                $levelUpgrade->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $levelUpgrade->bindParam(':id_spaceship', $id_spaceship, PDO::PARAM_INT);

                $levelUpgrade->execute();
                $levelCount = $levelUpgrade->fetch();
                $levelSpaceship = $levelCount[0];
                $priceSpaceship = $levelCount[1];
                $upgradePrice = $priceSpaceship * ($levelSpaceship + 1);

                $reqMerchant = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                $reqMerchant->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
                $reqMerchant->execute();
                $dataMerchant = $reqMerchant->fetch();
                $merchantFunds = $dataMerchant[0];

                if ($levelSpaceship > 2) {
                    echo "<h2>No more Upgrade possible</h2>";
                    echo "<p>You reached the level Treshold for this Spaceship</p>";
                } else {
                    if($merchantFunds > $upgradePrice){
                        $NewFunds = $merchantFunds - $upgradePrice;
                        $newLevel = $levelSpaceship + 1;
                        $reqNewFunds = $db->prepare("UPDATE merchant SET intergalactic_credits = :NewFunds
                                                            WHERE id_merchant = :id_merchant");
                        $reqNewFunds->bindParam(':NewFunds', $NewFunds, PDO::PARAM_INT);
                        $reqNewFunds->bindParam('id_merchant', $id_merchant, PDO::PARAM_INT);
                        $reqNewFunds->execute();

                        $reqSetUpgrade = $db->prepare("UPDATE merchant_spaceship SET level = :NewLevel
                                                              WHERE id_merchant = :id_merchant
                                                              AND id_spaceship = :id_spaceship");
                        $reqSetUpgrade->bindParam('NewLevel', $newLevel, PDO::PARAM_INT);
                        $reqSetUpgrade->bindParam('id_merchant', $id_merchant, PDO::PARAM_INT);
                        $reqSetUpgrade->bindParam(':id_spaceship', $id_spaceship, PDO::PARAM_INT);
                        $reqSetUpgrade->execute();

                        echo "<h2>Upgrade Successful</h2>";
                        echo "<p>Your Spaceship now have a better range for more mission!</p>";
                    }else{
                        echo "not enough credit to do the upgrade";
                    }
                }
                echo "<br><div><a href='inventory.php' role='btn' class='btn secondary'>Back to Inventory</a></div>";
                ?>
                <br>
            </div>
        </section>
    </article>
</main>
</body>
</html>
