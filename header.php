<?php
$id_merchant = $_SESSION['id_merchant'];
$db = new PDO("mysql:host=localhost; dbname=test1_projet; charset=utf8", "sa", "rasta");
$req = $db->prepare("SELECT first_name, last_name, intergalactic_credits, id_merchant FROM merchant WHERE id_merchant = :id_merchant");
$req->bindParam(':id_merchant', $id_merchant, PDO::PARAM_STR);
$req->execute();
$data = $req->fetch();
$first_name = $data[0];
$last_name = $data[1];
$intergalactic_credits = $data[2];
$id = $data[3];
?>

<nav>
    <ul>
        <li><a href="connect_to_account.php" role="btn" class="btn secondary">Log Out</a></li>
        <li><a href="home.php" role="btn" class="btn secondary">Home</a></li>
    </ul>
    <ul><li role="list" dir="rtl">
            <a href="" aria-haspopup="listbox">Buy now</a>
            <ul role="listbox">
                <li><a href="spaceship.php">Spaceship</a></li>
                <li><a href="recruit_crew.php">Crew Member</a></li>
            </ul>
        </li>
        <li><?php echo $first_name, " ", $last_name; ?></li>
        <li><?php echo "Credits: $intergalactic_credits ¢"; ?></li>
        <li><a href="inventory.php?id_merchant=<?php echo $id_merchant; ?>" role="btn" class="btn secondary">Inventory</a></li>
    </ul>
</nav>