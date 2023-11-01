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

