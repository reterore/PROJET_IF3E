<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Create Your Account</title>
</head>

<?php
$db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");

$first_name = "";
$last_name = "";
$login = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $login = $_POST["login"];
    $password = $_POST["password"];

    // Vérifiez si le login existe déjà dans la base de données
    $checkLogin = $db->prepare("SELECT COUNT(*) FROM space_merchant WHERE login = :login");
    $checkLogin->bindParam(':login', $login, PDO::PARAM_STR);
    $checkLogin->execute();
    $count = $checkLogin->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Login already exists. Please choose a different login.')</script>";
    } else {
        // Le login n'existe pas, insérez les données dans la base de données
        $insertUser = $db->prepare("INSERT INTO space_merchant (first_name, last_name, login, password, intergalactic_credit) VALUES (:first_name, :last_name, :login, :password, 3000)");
        $insertUser->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $insertUser->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $insertUser->bindParam(':login', $login, PDO::PARAM_STR);
        $insertUser->bindParam(':password', $password, PDO::PARAM_STR);

        if ($insertUser->execute()) {
            echo "<script>alert('Account created successfully. You can now login.')</script>";
        } else {
            echo "<script>alert('An error occurred while creating the account. Please try again.')</script>";
        }
    }
}
?>
<body>
<!-- Nav -->

<!-- Main -->
<main class="container">
    <article class="grid">
        <div>
            <hgroup>
                <h1>Create Your Account</h1>
                <h2>access a world of possibilities</h2>
            </hgroup>
            <form method="post">
                <input
                    type="text"
                    name="first_name"
                    placeholder="First name"
                    aria-label="first_name"
                    autocomplete="first_name"
                    required
                />
                <input
                    type="text"
                    name="last_name"
                    placeholder="Last name"
                    aria-label="last_name"
                    autocomplete="last_name"
                    required
                />
                <input
                    type="text"
                    name="login"
                    placeholder="Login"
                    aria-label="Login"
                    autocomplete="nickname"
                    required
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    aria-label="Password"
                    autocomplete="new-password"
                    required
                />
                <button type="submit" class="contrast">Create Account</button>
            </form>
        </div>
        <div></div>
    </article>
</main>
<!-- ./ Main -->

<!-- Footer -->

</body>
</html>
