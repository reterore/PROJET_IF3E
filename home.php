<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Welcome to homepage!</title>

    <style>
        .btn-container {
            display: flex;
            align-items: center;
        }</style>
</head>

<?php
session_start();
$id_merchant = $_SESSION['id_merchant'];
$db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "sa", "rasta");
$req = $db->prepare("SELECT first_name, last_name, intergalactic_credits, id_merchant FROM merchant WHERE id_merchant = :id_merchant");
$req->bindParam(':id_merchant', $id_merchant, PDO::PARAM_STR);
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
        <form method="post">
            <ul>
                <li role="list" dir="rtl">
                    <label for="selected_cargo">:Cargo</label>
                    <select name="selected_cargo" id="selected_cargo">
                        <option value="">All</option>
                        <?php
                        $cargos = $db->prepare("SELECT type FROM cargo_type ORDER BY type");
                        $cargos->execute();
                        while ($cargo = $cargos->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $cargo['type'] . "'>" . $cargo['type'] . "</option>";
                        }
                        ?>
                    </select>
                </li>
                <li role="list" dir="rtl">
                    <label for="selected_planet">:Planet</label>
                    <select name="selected_planet" id="selected_planet">
                        <option value="">All</option>
                        <?php
                        $planets = $db->prepare("SELECT name FROM planet ORDER BY name");
                        $planets->execute();
                        while ($planet = $planets->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $planet['name'] . "'>" . $planet['name'] . "</option>";
                        }
                        ?>
                    </select>
                </li>
                <li role="list" dir="rtl">
                    <label for="selected_ability">:ability</label>
                    <select name="selected_ability" id="selected_ability">
                        <option value="">All</option>
                        <?php
                        $abilities = $db->prepare("SELECT name FROM ability ORDER BY name");
                        $abilities->execute();
                        while ($ability = $abilities->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $ability['name'] . "'>" . $ability['name'] . "</option>";
                        }
                        ?>
                    </select>
                </li>
                <li role="list" dir="rtl">
                    <label for="selected_reward">:Minimal Reward</label>
                    <input type="text" name="selected_reward" id="selected_reward">
                </li>
                <li>
                    <br>
                    <button type="submit">>Filter Missions<</button>
                </li>
            </ul>
        </form>
    </nav>
    <header>
        <div class="btn-container">
            <a href="mission_explanation.php" role="btn" class="btn secondary">more about missions</a>
            <span>&nbsp;|&nbsp;</span>
            <a href="create_mission.php" role="btn" class="btn secondary">Create a mission</a>
        </div>
    </header>
    <section class="grid">
        <div>
            <?php
            $selected_cargo = isset($_POST['selected_cargo']) ? $_POST['selected_cargo'] : '';
            $selected_planet = isset($_POST['selected_planet']) ? $_POST['selected_planet'] : '';
            $selected_ability = isset($_POST['selected_ability']) ? $_POST['selected_ability'] : '';
            $selected_reward = isset($_POST['selected_reward']) ? (int)$_POST['selected_reward'] : 0;

            $query = $db->prepare("SELECT mission.name, cargo_type.type, planet.name, ability.name, reward, mission.id_mission 
                                  FROM mission 
                                  JOIN cargo_type ON mission.id_cargo_type = cargo_type.id_cargo_type
                                  JOIN planet ON mission.id_planet = planet.id_planet 
                                  JOIN ability ON mission.id_ability = ability.id_ability
                                  WHERE (:selected_cargo = '' OR cargo_type.type = :selected_cargo)
                                  AND (:selected_planet = '' OR planet.name = :selected_planet)
                                  AND (:selected_ability = '' OR ability.name = :selected_ability)
                                  AND mission.reward >= :selected_reward
                                  AND mission.done = 0
                                  AND mission.id_merchant <> :id_merchant  ;");
            $query->bindParam(':selected_cargo', $selected_cargo, PDO::PARAM_STR);
            $query->bindParam(':selected_planet', $selected_planet, PDO::PARAM_STR);
            $query->bindParam(':selected_ability', $selected_ability, PDO::PARAM_STR);
            $query->bindParam(':selected_reward', $selected_reward, PDO::PARAM_INT);
            $query->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
            $query->execute();

            if ($query->rowCount() > 0) {
                echo "<table border='1'>
                    <tr>
                        <th>Mission</th>
                        <th>Cargo</th>
                        <th>Planet</th>
                        <th>Ability</th>
                        <th>Reward</th>
                        <th>See details</th>
                    </tr>";

                while ($affichage = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>" . $affichage[0] . "</td>";
                    echo "<td>" . $affichage[1] . "</td>";
                    echo "<td>" . $affichage[2] . "</td>";
                    echo "<td>" . $affichage[3] . "</td>";
                    echo "<td>" . $affichage[4] . " ¢</td>";

                    echo "<td><a href='consult_mission.php?id_mission=" . $affichage[5] . "'>| more info |</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "No missions found based on your filter criteria.";
            }
            ?>
        </div>
    </section>
</main>
</body>

</html>
