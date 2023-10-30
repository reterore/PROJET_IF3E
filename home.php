<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Ajout de votre fichier CSS personnalisé -->
    <title>Welcome to homepage!</title>
</head>



<?php
session_start();
    $login = $_SESSION['login'];
    $db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");
    $req = $db->prepare("SELECT first_name, last_name, intergalactic_credits, id_space_merchant FROM space_merchant WHERE login = :login");
    $req->bindParam(':login', $login, PDO::PARAM_STR);
    $req->execute();
    $data = $req->fetch();
    $first_name = $data[0];
    $last_name = $data[1];
    $intergalactic_credits = $data[2];
    $id = $data[3];
?>

<body>
<?php
    include('header.php');
?>

<main class="container">
    <nav>
        <ul>
        </ul>
        <ul>
            <li><strong>Filter: </strong></li>
            <li role="list" dir="rtl">
                <a href="#" aria-haspopup="listbox">Type</a>
                <ul role="listbox">
                    <li><a>Action</a></li>
                    <li><a>Another action</a></li>
                    <li><a>Something else here</a></li>
                </ul>
            </li>
            <li role="list" dir="rtl">
                <a href="#" aria-haspopup="listbox">planet</a>
                <?php
                $planets = $db->prepare("SELECT name FROM planet");
                $planets->execute(); // Execute the prepared statement to fetch data

                while ($planet = $planets->fetch(PDO::FETCH_ASSOC)) {
                    echo "<ul role='listbox'>";
                    echo "<li>" . $planet['name'] . "</li>";
                    echo "</ul>";
                }
                ?>
            </li>
            <li></li>
            <li role="list" dir="rtl">
                <a href="#" aria-haspopup="listbox">Reward</a>
                <ul role="listbox">
                    <li><a>Action</a></li>
                    <li><a>Another action</a></li>
                    <li><a>Something else here</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <header>
        <a href="mission_explanation.php" role="btn" class="btn secondary">more about missions</a>
    </header>
    <section class="grid">
        <div>
            <?php
            // Votre code de connexion à la base de données

            $query = $db->prepare("SELECT mission.name, cargo_type.type, planet.name, ability.name, reward, mission.id_mission 
                                          FROM mission 
                                          JOIN cargo_type ON mission.id_cargo_type = cargo_type.id_cargo_type
                                          JOIN planet ON mission.id_planet = planet.id_planet 
                                          JOIN ability ON mission.id_ability = ability.id_ability");
            $query->execute();
            $affichage = $query->fetch();
            if ($query->rowCount() > 0) {
                echo "<table border='1'>
        <tr>
            <th>Mission</th>
            <th>Cargo</th>
            <th>Planet</th>
            <th>Ability</th>
            <th>Reward</th>
            <th>check details</th>
        </tr>";

                while ($affichage != null) {
                    echo "<tr>";
                    echo "<td>" . $affichage[0] . "</td>";
                    echo "<td>" . $affichage[1] . "</td>";
                    echo "<td>" . $affichage[2] . "</td>";  // Ah ça il aime pas dis donc
                    echo "<td>" . $affichage[3] . "</td>";
                    echo "<td>" . $affichage[4] . " ¢</td>";

                    echo "<td><a href='consult_mission.php?id=" . $affichage[5] . "'>more info</a></td>";
                    $affichage = $query->fetch();
                }

                echo "</table>";
            } else {
                echo "Plus aucune mission, revenez plus tard.";
            }
            ?>

        </div>
    </section>
</main>
</body>

</html>
