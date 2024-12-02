<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header("Location: connect.php");
    exit();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <center>
        <form action="bulletin.php" method="post">
            <h3>Entrer le Numero de Bultin</h3>
            <input type="number" name="num" id="num">
            <button type="submit" name="bulletin">Afficher Bulltin</button>
        </form>
        <a href="logout.php">Deconnecter</a>
    </center>
</body>

</html>