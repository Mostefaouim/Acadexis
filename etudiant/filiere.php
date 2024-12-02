<?php
require_once '../config.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}


if (isset($_POST['ajouter'])) {
    $nom_filiere = mysqli_real_escape_string($conn, $_POST['nom_filiere']);
    $query = "INSERT INTO filieres (nom_filiere) VALUES ('$nom_filiere')";
    mysqli_query($conn, $query) or die(mysqli_error($conn));
}
?>

<form method="post" action="">
    <label for="nom_filiere">Ajouter une filière :</label><br>
    <input type="text" name="nom_filiere"><br><br>
    <button type="submit" name="ajouter">Ajouter</button>
    <button type="submit" formaction="affichfiliere.php" name="affiche">Afficher list des filieres</button>
</form>