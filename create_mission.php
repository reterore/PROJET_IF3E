<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Create a New Mission</title>
</head>
<body>
<?php
session_start();
include('header.php');

$mission_name = "";
$cargo_type = "";
$planet = "";
$ability = "";
$reward = 0;
$description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $mission_name = $_POST["mission_name"];
    $cargo_type = $_POST["cargo_type"];
    $planet = $_POST["planet"];
    $ability = $_POST["ability"];
    $reward = $_POST["reward"];
    $description = $_POST["description"];

    // Récupérez l'ID du marchand à partir de la session, assurez-vous qu'il est correctement stocké dans $_SESSION['id_merchant']
    $id_merchant = $_SESSION['id_merchant'];

    // Récupérez le solde du marchand
    $reqCredits = $db->prepare("SELECT intergalactic_credits FROM merchant WHERE id_merchant = :id_merchant");
    $reqCredits->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
    $reqCredits->execute();
    $credits = $reqCredits->fetch()[0];

    // Vérifiez si le marchand a suffisamment d'argent pour créer la mission
    if ($reward <= $credits && $credits >= 1000) {
        if ($reward >=0){
            // Insérez la mission dans la base de données
            $insertMission = $db->prepare("INSERT INTO mission (name, id_cargo_type, id_planet, id_ability, id_merchant, reward, description) VALUES (:mission_name, :cargo_type, :planet, :ability, :merchant_id, :reward, :description)");
            $insertMission->bindParam(':mission_name', $mission_name, PDO::PARAM_STR);
            $insertMission->bindParam(':cargo_type', $cargo_type, PDO::PARAM_INT);
            $insertMission->bindParam(':planet', $planet, PDO::PARAM_INT);
            $insertMission->bindParam(':ability', $ability, PDO::PARAM_INT);
            $insertMission->bindParam(':merchant_id', $id_merchant, PDO::PARAM_INT);
            $insertMission->bindParam(':reward', $reward, PDO::PARAM_INT);
            $insertMission->bindParam(':description', $description, PDO::PARAM_STR);
            $insertMission->execute(); // Exécutez la requête pour insérer la mission
            $newFunds = $credits - 1000;
            $changeNewFunds = $db->prepare("UPDATE merchant SET intergalactic_credits = :newFunds WHERE id_merchant = :id_merchant");
            $changeNewFunds->bindParam(':newFunds', $newFunds, PDO::PARAM_INT);
            $changeNewFunds->bindParam(':id_merchant', $id_merchant, PDO::PARAM_INT);
            $changeNewFunds->execute();
            echo "<script>alert('mission created successfuly');</script>";
        }else{
            echo "<script>alert('your reward is not valid!');</script>";
        }
                // Réinitialisez les variables du formulaire
        $mission_name = "";
        $cargo_type = "";
        $planet = "";
        $ability = "";
        $reward = 0;
        $description = "";
    } else {
        echo "<script>alert('Insufficient funds to pay the reward or the fee.');</script>";
    }
}
?>

<main class="container">
    <article class="grid">
        <div>
            <hgroup>
                <h1>Create a New Mission</h1>
                <h2>explore new opportunities</h2>
            </hgroup>
            <form action="create_mission.php" method="post">
                <input
                        type="text"
                        name="mission_name"
                        placeholder="Mission Name"
                        aria-label="Mission Name"
                        required
                        value="<?php echo $mission_name; ?>"
                />
                <select name="cargo_type" required>
                    <option value="<?php echo $cargo_type; ?>">Select Cargo Type</option>
                    <?php
                    $cargoTypes = $db->query("SELECT id_cargo_type, type FROM cargo_type");
                    foreach ($cargoTypes as $row) {
                        $selected = ($row['id_cargo_type'] == $cargo_type) ? "selected" : "";
                        echo "<option value='" . $row['id_cargo_type'] . "' $selected>" . $row['type'] . "</option>";
                    }
                    ?>
                </select>
                <select name="planet" required>
                    <option value="<?php echo $planet; ?>">Select Planet</option>
                    <?php
                    $planets = $db->query("SELECT id_planet, name FROM planet");
                    foreach ($planets as $row) {
                        $selected = ($row['id_planet'] == $planet) ? "selected" : "";
                        echo "<option value='" . $row['id_planet'] . "' $selected>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
                <select name="ability" required>
                    <option value="<?php echo $ability; ?>">Select Ability</option>
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
                        value=""
                />
                <textarea
                        name="description"
                        placeholder="Mission Description"
                        aria-label="Mission Description"
                        rows="4"
                        required
                ><?php echo $description; ?></textarea>
                <button type="submit" class="Primary">Create Mission For 1000 ¢</button>
                <a href='home.php' role='button' class='secondary'>Cancel Creation</a>
            </form>
        </div>
    </article>
</main>
</body>
</html>
