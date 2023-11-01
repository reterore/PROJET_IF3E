<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Login to Your Account</title>
</head>

<?php
$db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "root", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];

    // Vérifiez si le login et le mot de passe correspondent dans la base de données
    $checkUser = $db->prepare("SELECT * FROM merchant WHERE login = :login AND password = :password");
    $checkUser->bindParam(':login', $login, PDO::PARAM_STR);
    $checkUser->bindParam(':password', $password, PDO::PARAM_STR);
    $checkUser->execute();
    $user = $checkUser->fetch();
    echo "$user[0]";
    if ($user != null) {
        // Redirigez vers la page d'accueil avec les paramètres d'UR
        session_start();
        $_SESSION['id_merchant'] = $user[0];
        header("Location: home.php");
        exit;
    } else {
        // Affiche un message d'alerte si le login ou le mot de passe est incorrect
        echo "<script>alert('Login or password is incorrect. Please try again.')</script>";
    }
}
?>

<body>
<!-- Nav -->

<!-- Main -->
<main class="container">
    <article class="grid">
        <a href="login.php" role="btn" class="btn secondary">go back</a>
        <div>
            <hgroup>
                <h1>Login to Your Account</h1>
                <h2>access a world of possibilities</h2>
            </hgroup>
            <form method="post">
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
                        autocomplete="current-password"
                        required
                />
                <button type="submit" class="contrast">Login</button>
            </form>
            <a href="Create_account.php" role="btn" class="btn contrast">no account? create one now!</a>
        </div>
        <div></div>
    </article>
</main>

</body>
</html>
