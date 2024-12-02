<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin</title>
</head>

<body>
    <div>
        <form action="manage.php" method="post">
            <label for="num">Recharche Etudiants</label>
            <input type="number" name="num" id="num">
            <button type="submit" name="rechercher">Recharche</button>
            <button type="submit" name="AffichageListe" formaction="display.php">Affichage Liste</button>
        </form>
    </div><br><br>
    <div>
        <form action="bulletin.php" method="post">
            <label for="num">Voir Bulletin De Notes</label>
            <input type="number" name="num" id="num" placeholder="Entrer Le Numero d'Etudiant">
            <button type="submit" name="bulletin" formaction="bulletin.php">Relever De Note</button>
        </form>
    </div>

</body>

</html>