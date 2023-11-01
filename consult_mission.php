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
                                <br>
                                <?php
                                // Vérifiez si id_mission est défini dans la requête GET
                                if (isset($_GET['id_mission'])) {
                                    // Assurez-vous que l'ID de la mission est un nombre entier
                                    $id_mission = $_GET['id_mission'];
                                    // Connexion à la base de données (à remplacer par vos propres informations de connexion)

                                    // Préparez la requête pour récupérer les détails de la mission
                                    $query = $db->prepare("SELECT mission.name, cargo_type.type, planet.name, ability.name, reward, mission.description 
                                                            FROM mission 
                                                            JOIN cargo_type ON mission.id_cargo_type = cargo_type.id_cargo_type
                                                            JOIN planet ON mission.id_planet = planet.id_planet 
                                                            JOIN ability ON mission.id_ability = ability.id_ability
                                                            WHERE id_mission = :id_mission");
                                    $query->bindParam(':id_mission', $id_mission, PDO::PARAM_INT);
                                    $query->execute();
                                    $mission = $query->fetch();
                                    // Vérifiez si la mission a été trouvée
                                    if ($mission != null) {
                                        // Affichez les détails de la mission
                                        echo "<h1>Summary of Mission:<p> " . $mission[0] . "</p></h1>";
                                        echo "<p><strong>Cargo Type:</strong> " . $mission[1] . "</p>";
                                        echo "<p><strong>Planet:</strong> " . $mission[2] . "</p>";
                                        echo "<p><strong>Useful Ability:</strong> " . $mission[3] . "</p>";
                                        echo "<p><strong>Mission Reward:</strong> " . $mission[4] . " ¢</p>";
                                        echo "<p><strong>Description:</strong> " . $mission[5] . "</p>";
                                        echo "<a href='home.php' role='button' class='secondary'>Go Back to Mission</a>";
                                        echo" ";
                                        echo "<a href='start_mission.php?$id_mission' role='button' class='btn primary'>Start this Mission</a>";
                                        // Ajoutez ici d'autres détails de la mission si nécessaire
                                    } else {
                                        echo "Mission not found.";
                                    }
                                } else {
                                    echo "Mission ID not provided.";
                                }
                                ?>
                            </div>
                        </section>
                    </article>
</main>
</body>
</html>
