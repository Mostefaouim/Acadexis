<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}
if (isset($_POST['ajouter'])) {
    $nom_module = $_POST['nom_module'];
    $code_module = $_POST['code_module'];
    $designation_module = $_POST['designation_module'];
    $coefficient = $_POST['coefficient'];
    $volume_horaire = $_POST['volume_horaire'];
    $filiere_id = $_POST['filiere_id'];

    $query = "INSERT INTO modules (nom, code_module, designation_module, coefficient, volume_horaire, filiere_id)
        VALUES ('$nom_module','$code_module', '$designation_module', $coefficient, $volume_horaire, $filiere_id)";
    if (mysqli_query($conn, $query)) {
        echo "<font color='green'>Module ajouté avec succès.</font><br>";
    } else {
        die('Erreur : ' . mysqli_error($conn));
    }
}

$filieres = mysqli_query($conn, "SELECT * FROM filieres");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
        }

        label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div>
        <form action="manage.php" method="post" enctype="multipart/form-data">
            <label for="numero">Recharge Module:</label>
            <input name="id" id="numero" type="number" min="1">
            <input type="submit" value="Search" name="search">
        </form>
    </div>
    <br>
    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="nom_module">Nom Module :</label>
            <input type="text" name="nom_module">
        </div><br>
        <div>
            <label for="code_module">Code Module :</label>
            <input type="text" name="code_module">
        </div><br>

        <div>
            <label for="designation_module">Désignation :</label>
            <input type="text" name="designation_module">
        </div><br>
        <div>
            <label for="coefficient">Coefficient :</label>
            <input type="number" step="0.01" name="coefficient">
        </div><br>
        <div>
            <label for="volume_horaire">Volume Horaire :</label>
            <input type="number" name="volume_horaire">
        </div><br>
        <div>
            <label for="filiere_id">Filière :</label>
            <select name="filiere_id">
                <option value=""></option>
                <?php while ($row = mysqli_fetch_assoc($filieres)) { ?>
                    <option value="<?= $row['id']; ?>"><?= $row['nom_filiere']; ?></option>
                <?php } ?>
            </select>
        </div><br>

        <button type="submit" name="ajouter">Ajouter</button>
        <button type="submit" formaction="affichModl.php" name="afficheList">Afficher La List</button>
    </form>
    <br>

    <script src="../script.js"></script>
</body>

</html>