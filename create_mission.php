<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Create a New Mission</title>
</head>

<?php
session_start();
include('header.php');
?>

<?php

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
    $reward = $_POST["reward"];
    $description = $_POST["description"];

    $insertMission = $db->prepare("INSERT INTO mission (name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES (:mission_name, :cargo_type, :planet, :ability, 1, :reward, :description)");
    $insertMission->bindParam(':mission_name', $mission_name, PDO::PARAM_STR);
    $insertMission->bindParam(':cargo_type', $cargo_type, PDO::PARAM_INT);
    $insertMission->bindParam(':planet', $planet, PDO::PARAM_INT);
    $insertMission->bindParam(':ability', $ability, PDO::PARAM_INT);
    $insertMission->bindParam(':reward', $reward, PDO::PARAM_INT);
    $insertMission->bindParam(':description', $description, PDO::PARAM_STR);

    if ($insertMission->execute()) {
        echo "<script>alert('Mission created successfully.');</script>";
        $mission_name = "";
        $cargo_type = "";
        $planet = "";
        $ability = "";
        $reward = 0;
        $description = "";
    } else {
        echo "<script>alert('An error occurred while creating the mission. Please try again.');</script>";
    }
}
?>

<body>
<main class="container">
    <article class="grid">
        <div>
            <hgroup>
                <h1>Create a New Mission</h1>
                <h2>explore new opportunities</h2>
            </hgroup>
            <form action="confirm_creation_mission.php" method="post">
                <input
                    type="text"
                    name="mission_name"
                    placeholder="Mission Name"
                    aria-label="Mission Name"
                    required
                />
                <select name="cargo_type" required>
                    <option value="">Select Cargo Type</option>
                    <?php
                    $cargoTypes = $db->query("SELECT id_cargo_type, type FROM cargo_type");
                    foreach ($cargoTypes as $row) {
                        echo "<option value='" . $row['id_cargo_type'] . "'>" . $row['type'] . "</option>";
                    }
                    ?>
                </select>
                <select name="planet" required>
                    <option value="">Select Planet</option>
                    <?php
                    $planets = $db->query("SELECT id_planet, name FROM planet");
                    foreach ($planets as $row) {
                        echo "<option value='" . $row['id_planet'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
                <select name="ability" required>
                    <option value="">Select Ability</option>
                    <?php
                    $abilities = $db->query("SELECT id_ability, name FROM ability");
                    foreach ($abilities as $row) {
                        echo "<option value='" . $row['id_ability'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
                <input
                    type="number"
                    name="reward"
                    placeholder="Reward"
                    aria-label="Reward"
                    required
                />
                <textarea
                    name="description"
                    placeholder="Mission Description"
                    aria-label="Mission Description"
                    rows="4"
                    required
                ></textarea>
                <button href='confirm_creation_mission' type="submit" class="contrast">Create Mission</button>
                <a href='home.php' role='button' class='secondary'>Cancel Creation</a>
            </form>
        </div>
    </article>

</main>
</body>
</html>

