<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Modify a Mission</title>
</head>
<body>
<?php
session_start();
include('header.php');

if (isset($_GET['id_mission'])) {
    $id_mission = $_GET['id_mission'];
}

$id_merchant = $_SESSION['id_merchant'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mission_name = $_POST['mission_name'];
    $cargo_type = $_POST['cargo_type'];
    $planet = $_POST['planet'];
    $ability = $_POST['ability'];
    $reward = $_POST['reward'];
    $description = $_POST['description'];
    $id_mission = $_POST['id_mission'];

    $updateQuery = $db->prepare("UPDATE mission
                                SET name = :mission_name,
                                    id_cargo_type = :cargo_type,
                                    id_planet = :planet,
                                    id_ability = :ability,
                                    reward = :reward,
                                    description = :description
                                WHERE id_mission = :id_mission");

    $updateQuery->bindParam(':mission_name', $mission_name, PDO::PARAM_STR);
    $updateQuery->bindParam(':cargo_type', $cargo_type, PDO::PARAM_INT);
    $updateQuery->bindParam(':planet', $planet, PDO::PARAM_INT);
    $updateQuery->bindParam(':ability', $ability, PDO::PARAM_INT);
    $updateQuery->bindParam(':reward', $reward, PDO::PARAM_INT);
    $updateQuery->bindParam(':description', $description, PDO::PARAM_STR);
    $updateQuery->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);

    if ($updateQuery->execute()) {
        echo "<p>Mission updated successfully.</p>";
    } else {
        echo "<p>Error updating the mission.</p>";
    }
}

$req1 = $db->prepare("SELECT mission.name, cargo_type.type, planet.name, ability.name, reward, mission.description
                    FROM mission
                    JOIN cargo_type ON mission.id_cargo_type = cargo_type.id_cargo_type
                    JOIN planet ON mission.id_planet = planet.id_planet
                    JOIN ability ON mission.id_ability = ability.id_ability
                    WHERE mission.id_mission = :id_mission");
$req1->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
$req1->execute();
$dataMission = $req1->fetch();
$mission_name = $dataMission[0];
$cargo_type = $dataMission[1];
$planet = $dataMission[2];
$ability = $dataMission[3];
$reward = $dataMission[4];
$description = $dataMission[5];
?>
<main class="container">
    <article class="grid">
        <div>
            <hgroup>
                <h1>Modify this Mission</h1>
                <h2>Change your mind anytime</h2>
            </hgroup>
            <form action="modify_mission.php" method="post">
                <input type="hidden" name="id_mission" value="<?php echo $id_mission; ?>">
                <input
                        type="text"
                        name="mission_name"
                        placeholder="Mission Name"
                        aria-label="Mission Name"
                        required
                        value="<?php echo $mission_name; ?>"
                />
                <select name="cargo_type" required>
                    <?php
                    $cargoTypes = $db->query("SELECT id_cargo_type, type FROM cargo_type");
                    foreach ($cargoTypes as $row) {
                        $selected = ($row['id_cargo_type'] == $cargo_type) ? "selected" : "";
                        echo "<option value='" . $row['id_cargo_type'] . "' $selected>" . $row['type'] . "</option>";
                    }
                    ?>
                </select>
                <select name="planet" required>
                    <?php
                    $planets = $db->query("SELECT id_planet, name FROM planet");
                    foreach ($planets as $row) {
                        $selected = ($row['id_planet'] == $planet) ? "selected" : "";
                        echo "<option value='" . $row['id_planet'] . "' $selected>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
                <select name="ability" required>
                    <?php
                    $abilities = $db->query("SELECT id_ability, name FROM ability");
                    foreach ($abilities as $row) {
                        $selected = ($row['id_ability'] == $ability) ? "selected" : "";
                        echo "<option value='" . $row['id_ability'] . "' $selected>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
                <input
                        type="number"
                        name="reward"
                        placeholder="Reward"
                        aria-label="Reward"
                        required
                        value="<?php echo $reward; ?>"
                />
                <textarea
                        name="description"
                        placeholder="Mission Description"
                        aria-label="Mission Description"
                        rows="4"
                        required
                ><?php echo $description; ?></textarea>
                <button type="submit" class="primary">Modify Mission</button>
                <a href='home.php' role='button' class='secondary'>Cancel Modification</a>
            </form>
        </div>
    </article>
</main>
</body>
</html>
