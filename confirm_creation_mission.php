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
                $db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");

                $mission_name = "";
                $cargo_type = "";
                $planet = "";
                $ability = "";
                $reward = 0;
                $description = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $mission_name = $_POST["mission_name"];
                    $cargo_type = $_POST["cargo_type"];
                    $planet = $_POST["planet"];
                    $ability = $_POST["ability"];
                    $id_merchant = $_SESSION['id_merchant'];
                    $reward = $_POST["reward"];
                    $description = $_POST["description"];

                    $insertMission = $db->prepare("INSERT INTO mission (name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES (:mission_name, :cargo_type, :planet, :ability, :id_merchant, :reward, :description)");
                    $insertMission->bindParam(':mission_name', $mission_name, PDO::PARAM_STR);
                    $insertMission->bindParam(':cargo_type', $cargo_type, PDO::PARAM_INT);
                    $insertMission->bindParam(':planet', $planet, PDO::PARAM_INT);
                    $insertMission->bindParam(':ability', $ability, PDO::PARAM_INT);
                    $insertMission->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT); // Notez la correction ici
                    $insertMission->bindParam(':reward', $reward, PDO::PARAM_INT);
                    $insertMission->bindParam(':description', $description, PDO::PARAM_STR);

                    if ($insertMission->execute()) {
                        echo "Mission created successfully!";
                        $mission_name = "";
                        $cargo_type = "";
                        $planet = "";
                        $ability = "";
                        $reward = 0;
                        $description = "";
                        echo "<br><br><div><a href='home.php' role='btn' class='btn secondary'>Back to Mission</a></div>";
                    } else {
                        echo "An error occurred while creating the mission. Please try again!";
                        echo "<br><div><a href='home.php' role='btn' class='btn secondary'>Back to Mission</a></div>";
                    }
                }
                ?>
            </div>
        </section>
    </article>
</main>
</body>
</html>
