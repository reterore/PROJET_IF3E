<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Create an Account</title>
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

    $req = $db->prepare("SELECT COUNT(*) FROM space_merchant WHERE login = :login");
    $req->bindParam(':login', $login, PDO::PARAM_STR);
    $req->execute();
    $count = $req->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Login already exists. Please choose a different login.')</script>";
    } else {
        $password = $_POST["password"];

        // Insérer les données dans la base de données
        $insertUser = $db->prepare("INSERT INTO space_merchant (first_name, last_name, login, password, intergalactic_credit) VALUES (:first_name, :last_name, :login, :password, 3000)");
        $insertUser->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $insertUser->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $insertUser->bindParam(':login', $login, PDO::PARAM_STR);
        $insertUser->bindParam(':password', $password, PDO::PARAM_STR);

        if ($insertUser->execute()) {
            echo "<script>alert('Account created successfully. You can now login.')</script>";
            echo "<script>setTimeout(function() { window.location.href = 'connect_to_account.php'; }, 500);</script>";
            exit;
        } else {
            echo "<script>alert('An error occurred while creating the account. Please try again.')</script>";
        }
    }
}
?>

<body>
<div class="container">
    <h1>Open Yourself to Another Dimension - Create an Account</h1>

    <form action="create_account.php" method="post">

        <label for="first_name">First Name :</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($first_name); ?>" required class="input">

        <label for="last_name">Last Name :</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($last_name); ?>" required class="input">

        <label for="login">Login :</label>
        <input type="text" name="login" required class="input">

        <label for="password">Password :</label>
        <input type="password" name="password" required class="input">
        <div class="button-container">
            <button type="submit" class="btn">Create Account</button>
        </div>
    </form>
</div>
</body>
</html>
