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
                if (isset($_GET['id_crew_member'])) {
                    $id_crew_member = $_GET['id_crew_member'];

                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $query = $db->prepare("SELECT first_name, last_name, id_ability, recruitment_price FROM crew_member WHERE id_crew_member = :id_crew_member");
                    $query->bindParam(':id_crew_member', $id_crew_member, PDO::PARAM_INT);
                    $query->execute();
                    $crewMember = $query->fetch();

                    if ($crewMember != null) {
                        $spaceMerchantId = $_SESSION['id_merchant'];
                        $recruitmentPrice = $crewMember['recruitment_price'];

                        $checkFundsQuery = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
                        $checkFundsQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_STR);
                        $checkFundsQuery->execute();
                        $spaceMerchantFunds = $checkFundsQuery->fetch();

                        echo "<h2>Crew Member Details</h2>";
                        echo "<p><strong>First Name:</strong> " . $crewMember['first_name'] . "</p>";
                        echo "<p><strong>Last Name:</strong> " . $crewMember['last_name'] . "</p>";
                        $abilityQuery = $db->prepare("SELECT name FROM ability WHERE id_ability = :id_ability");
                        $abilityQuery->bindParam(':id_ability', $crewMember['id_ability'], PDO::PARAM_INT);
                        $abilityQuery->execute();
                        $ability = $abilityQuery->fetch();
                        if ($ability) {
                            echo "<p><strong>Ability:</strong> " . $ability['name'] . "</p>";
                        } else {
                            echo "<p><strong>Ability:</strong> Unknown Ability</p>";
                        }
                        echo "<p><strong>Recruitment Price:</strong> " . $crewMember['recruitment_price'] . " ¢</p>";

                        if ($spaceMerchantFunds[0] >= $recruitmentPrice) {
                            echo "<a href='recruit_crew.php' role='button' class='secondary'>Cancel recruitment</a>";
                            echo" ";
                            echo "<a href='validate_recruitment.php?id_crew_member=$id_crew_member&recruitment_price=" . $crewMember['recruitment_price'] . "' role='button' class='btn primary'>Confirm recruitment</a>";
                        } else {
                            echo "Insufficient funds. You cannot recruit the crew member.</br></br>";
                            echo "<a href='recruit_crew.php' role='button' class='secondary'>Back to recruitment</a>";
                            echo " ";
                            echo "<a href='home.php' role='button' >Go to missions</a>";
                        }
                    } else {
                        echo "Invalid crew member selection.";
                    }
                } else {
                    echo "Crew member not selected. Please go back to the crew recruitment page.";
                }

                ?>
                <br>
            </div>
        </section>
    </article>
</main>
</body>
</html>
