<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Choose Crew Members</title>
</head>
<body>

<?php
session_start();
include('header.php');
?>

<main class="container">
    <article class="grid">
        <div class="col-4">
            <?php
            if (isset($_SESSION['id_merchant'])) {
                $spaceMerchantId = $_SESSION['id_merchant'];
                $selectedSpaceship = isset($_POST['selected_spaceship']) ? $_POST['selected_spaceship'] : "";
                echo "<h2>Selected Spaceship: $selectedSpaceship</h2>";
                $infoQuery = $db->prepare("SELECT crew_capacity, cargo_capacity_ton, max_travel_range_parsec
                                FROM spaceship WHERE name = :name");
                $infoQuery->bindParam(':name', $selectedSpaceship, PDO::PARAM_STR);
                $infoQuery->execute();
                $info = $infoQuery->fetch();
                echo "<ul>";
                echo "<li><strong>Crew Capacity:</strong> {$info['crew_capacity']}</li>";
                echo "<li><strong>Cargo Capacity:</strong> {$info['cargo_capacity_ton']} kg</li>";
                echo "<li><strong>Maximum Travel Range in Parsecs:</strong> {$info['max_travel_range_parsec']}</li>";
                echo "</ul>";

                $id_mission = $_POST['id_mission'];
                // Vérifiez le nombre de membres d'équipage disponibles
                $crewMembersQuery = $db->prepare("SELECT COUNT(*) as total_crew_members
                            FROM merchant_crew
                            WHERE id_merchant = :id_merchant");
                $crewMembersQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                $crewMembersQuery->execute();
                $crewMemberCount = $crewMembersQuery->fetchColumn();

                if ($info['crew_capacity'] <= $crewMemberCount) {
                    // Assez de membres d'équipage, affichez-les dans des colonnes distinctes
                    echo "<h2>Crew Members:</h2>";
                    echo "<form method='post' action='mission_results.php'>";
                    echo "<input type='hidden' name='selected_spaceship' value='$selectedSpaceship'>";
                    echo "<div class='grid'>";

                    $crewMembersQuery = $db->prepare("SELECT cm.first_name, cm.last_name, a.name
                            FROM merchant_crew mc
                            INNER JOIN crew_member cm ON mc.id_crew_member = cm.id_crew_member
                            INNER JOIN ability a ON cm.id_ability = a.id_ability
                            WHERE mc.id_merchant = :id_merchant");
                    $crewMembersQuery->bindParam(':id_merchant', $spaceMerchantId, PDO::PARAM_INT);
                    $crewMembersQuery->execute();
                    $crewMembers = $crewMembersQuery->fetchAll();

                    $crewCapacity = $info['crew_capacity'];

                    for ($i = 1; $i <= $crewCapacity; $i++) {
                        echo "<div class='col-6'>";
                        echo "<label for='crew_member_$i'>Crew Member $i:</label>";
                        echo "<select name='selected_crew_members[]' id='crew_member_$i'>";
                        echo "<option value=''>-- Select --</option>";

                        foreach ($crewMembers as $crewMember) {
                            $full_name = $crewMember['first_name'] . ' ' . $crewMember['last_name'] . ' (' . $crewMember['name'] . ')';
                            echo "<option value='$full_name'>$full_name</option>";
                        }

                        echo "</select>";
                        echo "</div>";
                    }
                    echo "<input type='hidden' name='selected_spaceship' value='$selectedSpaceship'>";
                    echo "<input type='hidden' name='id_mission' value='$id_mission'>";
                    echo "</div>";
                    echo "<input type='submit' value='Select Crew Members' onclick='return validateForm()'>";
                    echo "</form>";
                } else {
                    // Pas assez de membres d'équipage, proposez d'en acheter de nouveaux
                    echo "You don't have enough crew members. <a href='recruit_crew.php' role='button' class='btn primary'>Buy More Crew Members</a>";
                }
            } else {
                echo "You must be logged in to view crew member data.";
            }
            ?>
        </div>
    </article>
</main>

<script>
    function validateForm() {
        var selects = document.querySelectorAll("select");
        var selectedValues = Array.from(selects).map(select => select.value);

        // Vérifie si au moins un des selects est vide
        if (selectedValues.includes("")) {
            alert("Veuillez sélectionner un membre d'équipage pour chaque place dans le vaisseau.");
            return false;
        }

        // Vérifie si deux sélections sont égales
        if (hasDuplicates(selectedValues)) {
            alert("Veuillez sélectionner des membres d'équipage distincts pour chaque place dans le vaisseau.");
            return false;
        }

        return true;
    }

    function hasDuplicates(array) {
        return (new Set(array)).size !== array.length;
    }
</script>
</body>
</html>
