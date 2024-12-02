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
    <title>Sport Formulair</title>
</head>

<body>
    <form action="affichsport.php" method="post">
        <label for="sport">Sport:</label><br>
        <input type="text" name="sport" id="sport"><br><br>
        <input type="submit" value="Enregitrer" name="save">
        <input type="submit" value="Afficher List" name="affiche">
    </form>
</body>

</html>