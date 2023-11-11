<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Create Your Account</title>
    <style>
        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>

<?php
$db = new PDO("mysql:host=localhost; dbname=space_merchant; charset=utf8", "sa", "rasta");

$first_name = "";
$last_name = "";
$login = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $login = $_POST["login"];
    $password = $_POST["password"];

    $checkLogin = $db->prepare("SELECT COUNT(*) FROM merchant WHERE login = :login");
    $checkLogin->bindParam(':login', $login, PDO::PARAM_STR);
    $checkLogin->execute();
    $count = $checkLogin->fetchColumn();

    if ($count > 0) {
        $error_message = "Sorry, this login is already taken. Please choose a different one.";
    } else {
        $insertUser = $db->prepare("INSERT INTO merchant (first_name, last_name, login, password, intergalactic_credits) VALUES (:first_name, :last_name, :login, :password, 5000)");
        $insertUser->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $insertUser->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $insertUser->bindParam(':login', $login, PDO::PARAM_STR);
        $insertUser->bindParam(':password', $password, PDO::PARAM_STR);

        if ($insertUser->execute()) {
            header("Location: connect_to_account.php");
        } else {
            $error_message = "An error occurred while creating the account. Please try again.";
        }
    }
}
?>
<body>

<main class="container">
    <article class="grid">
        <a href="index.php" role="btn" class="btn secondary">go back</a>
        <div>
            <hgroup>
                <h1>Create Your Account</h1>
                <h2>Access a world full of possibilities</h2>
            </hgroup>
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>
            <form method="post">
                <input
                        type="text"
                        name="first_name"
                        placeholder="First name"
                        aria-label="first_name"
                        autocomplete="first_name"
                        required
                        value="<?= htmlspecialchars($first_name) ?>"
                />
                <input
                        type="text"
                        name="last_name"
                        placeholder="Last name"
                        aria-label="last_name"
                        autocomplete="last_name"
                        required
                        value="<?= htmlspecialchars($last_name) ?>"
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
            <a href="connect_to_account.php?login=" role="btn" class="btn contrast">Already have an account?</a>
        </div>
        <div></div>
    </article>
</main>
</body>
</html>
