<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}
if (isset($_POST['ajouter'])) {

    $numero =  $_POST['numero'];
    $civilite =  $_POST['civility'] ?? '';
    $nom_prenom =  $_POST['nom_prenom'];
    $email =  $_POST['email'];
    $adresse =  $_POST['adresse'];
    $date_naissance =  $_POST['date_naissance'];
    $lieu_naissance =  $_POST['lieu_naissance'];
    $pays =  $_POST['Pays'] ?? null;
    $grade =  $_POST['grade'];
    $specialite =  $_POST['specialite'];
    $univ =  $_POST['univ'] ?? null;

    $query = "INSERT INTO enseignants (numero, civilite, nom_prenom,email ,adresse, date_naissance, lieu_naissance, nationalite_id, grade, specialite, univ_id) 
              VALUES ('$numero', '$civilite', '$nom_prenom','$email', '$adresse', '$date_naissance', '$lieu_naissance', '$pays', '$grade', '$specialite', '$univ')";

    if (mysqli_query($conn, $query)) {
        echo "<font color='green'>Enseignant ajouté avec succès.</font><br>";
    } else {
        die('Erreur : ' . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enseignants</title>
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
            <label for="numero">Recharge Enseignant :</label>
            <input name="id" id="numero" type="number" min="1">
            <input type="submit" value="Search" name="search">
        </form>
    </div>
    <br>

    <form method="post" enctype="multipart/form-data">

        <div>
            <label for="numero">Numéro :</label>
            <input type="text" name="numero">
        </div><br>

        <div>
            <label for="civility">Civilité:</label>
            <input type="radio" id="monsieur" name="civility" value="Monsieur">
            <label for="monsieur">Monsieur</label>
            <input type="radio" id="madame" name="civility" value="Madame">
            <label for="madame">Madame</label>
            <input type="radio" id="mademoiselle" name="civility" value="Mademoiselle">
            <label for="mademoiselle">Mademoiselle</label>
        </div><br>

        <div id="imageContainer" style="float: right;"></div>
        <br>
        <div>
            <label for="nom_prenom">Nom/Prénom :</label>
            <input type="text" name="nom_prenom">
        </div>
        <div>
            <label for="email">email :</label>
            <input type="text" name="email">
        </div>
        <br>
        <div>
            <label for="adresse">Adresse :</label><br>
            <textarea name="adresse" id="adresse"></textarea>
        </div>
        <br>
        <div>
            <label for="date_naissance">Date de Naissance :</label>
            <input type="date" name="date_naissance">
        </div><br>
        <div>
            <label for="lieu_naissance">Lieu de Naissance :</label>
            <input type="text" name="lieu_naissance">
        </div><br>


        <div>
            <label for="Pays">Pays :</label>
            <select name="Pays">
                <option value="">Choisir Votre Pays</option>
                <?php
                $pays = mysqli_query($conn, "SELECT * FROM nationalité");
                if (!$pays) {
                    die("Database query failed: " . mysqli_error($conn));
                }

                while ($row = mysqli_fetch_assoc($pays)) { ?>
                    <option value="<?= $row['id']; ?>"><?= $row['nationalite']; ?></option>
                <?php } ?>
            </select>

        </div>

        <br>

        <div>
            <label for="grade">Grade :</label>
            <select name="grade">
                <option value="">Choisir Votre Grad</option>
                <option value="Assistant">Assistant</option>
                <option value="MAB">MAB</option>
                <option value="MAA">MAA</option>
                <option value="MCB">MCB</option>
                <option value="MCA">MCA</option>
                <option value="Professeur">Professeur</option>
            </select>
        </div><br>

        <div>
            <label for="specialite">Spécialité :</label>
            <select name="specialite">
                <option value="">Choisir Votre Spécialité</option>
                <option value="Informatique">Informatique</option>
                <option value="Mathématiques">Mathématiques</option>
                <option value="Anglais">Anglais</option>
                <option value="Autres">Autres</option>
            </select>
        </div>
        <br>
        <div>
            <label for="univ">Universite :</label>
            <select name="univ">
                <option value="">Choisir Votre Universite</option>
                <?php
                $univ = mysqli_query($conn, "SELECT * FROM universite");
                if (!$univ) {
                    die("Database query failed: " . mysqli_error($conn));
                }

                while ($row1 = mysqli_fetch_assoc($univ)) { ?>
                    <option value="<?= $row1['id']; ?>"><?= $row1['nom']; ?></option>
                <?php } ?>
            </select>

        </div><br>


        <button type="submit" name="ajouter">Ajouter</button>
        <button type="submit" formaction="afficheens.php" name="afficheList">Afficher List Des Enseignants</button>
    </form>

    <br>


    <script src="../script.js"></script>
    <script>
        let fontElements = document.getElementsByTagName('font');
        setTimeout(() => {
            for (let i = 0; i < fontElements.length; i++) {
                fontElements[i].style.display = "none";
            }
        }, timeout);
    </script>
</body>

</html>