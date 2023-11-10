<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Login to Your Account</title>
</head>

<?php
$db = new PDO("mysql:host=localhost; dbname=space_merchant; charset=utf8", "sa", "rasta");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];

    $checkUser = $db->prepare("SELECT * FROM merchant WHERE login = :login AND password = :password");
    $checkUser->bindParam(':login', $login, PDO::PARAM_STR);
    $checkUser->bindParam(':password', $password, PDO::PARAM_STR);
    $checkUser->execute();
    $user = $checkUser->fetch();
    if ($user != null) {
        session_start();
        $_SESSION['id_merchant'] = $user[0];
        header("Location: home.php");
        exit;
    } else {
        echo "<script>alert('Login or password is incorrect. Please try again.')</script>";
    }
}
?>

<body>
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
