<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Welcome to homepage!</title>
</head>

<?php
// Vérifiez l'authentification de l'utilisateur ici, par exemple en utilisant des sessions.

// Si l'utilisateur n'est pas authentifié, redirigez-le vers la page de connexion.
// Exemple de vérification d'authentification :
// if (!isset($_SESSION["user_id"])) {
//     header("Location: login.php");
//     exit;
// }

$db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");

// Récupérez les informations de l'utilisateur connecté
$user_id = $_SESSION["id_space_merchant"]; // Assurez-vous d'ajuster cette variable de session en fonction de votre implémentation d'authentification

$req = $db->prepare("SELECT first_name, last_name FROM space_merchant WHERE id = :user_id");
$req->bindParam(':id_space_merchant', $user_id, PDO::PARAM_INT);
$req->execute();
$userData = $req->fetch(PDO::FETCH_ASSOC);

$first_name = $userData["first_name"];
$last_name = $userData["last_name"];
?>

<body>
<div class="container">
    <h1>Welcome, <?php echo $first_name; ?>!</h1>
    <p>Your last name: <?php echo $last_name; ?></p>
    <!-- Ajoutez ici le contenu de votre page d'accueil -->
</div>
</body>
</html>