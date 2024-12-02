
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
    <title>Universite Formulair</title>
</head>

<body>
    <form action="affichuniv.php" method="post">
        <label for="univ">universite:</label><br>
        <input type="text" name="univ" id="sport"><br><br>
        <input type="submit" value="Enregitrer" name="save">
        <input type="submit" value="Afficher List" name="affiche">
    </form>
</body>

</html>