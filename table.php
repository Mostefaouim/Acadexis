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
    <title>Document</title>
</head>

<body>
    <form action="" method="post">

        <button type="submit" formaction="etudiant/nationalite.php">Nationalite</button>
        <button type="submit" formaction="etudiant/sport.php">Sport</button>
        <button type="submit" formaction="etudiant/filiere.php">Filiere</button>
        <button type="submit" formaction="Enseignants/universite.php">Universite</button>
    </form>
</body>

</html>