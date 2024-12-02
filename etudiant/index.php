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
    <title>Etudiant</title>
    <style>
        /* Basic styling for better visibility */
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
    <?php
    // Connexion à la base de données
    require '../config.php';

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    ?>

    <div>
        <form action="manage.php" method="post" enctype="multipart/form-data">
            <label for="numero">Recharge Etudiant:</label>
            <input name="id" id="numero" type="number" min="1">
            <input type="submit" value="Search" name="search">
        </form>
    </div>

    <form method="post" action="web.php" enctype="multipart/form-data">
        <br>
        <div>
            <label for="civility">Civilité:</label>
            <input type="radio" id="monsieur" name="civility" value="Monsieur">
            <label for="monsieur">Monsieur</label>
            <input type="radio" id="madame" name="civility" value="Madame">
            <label for="madame">Madame</label>
            <input type="radio" id="mademoiselle" name="civility" value="Mademoiselle">
            <label for="mademoiselle">Mademoiselle</label>
        </div>

        <div id="imageContainer" style="float: right;"></div>
        <br>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <div>
            <label for="nom">Nom/Prenom:</label>
            <input type="text" id="nom" name="nom">
        </div>
        <br>

        <label for="adresse">Adresse:</label>
        <div>
            <textarea id="adresse" name="adresse" rows="4" cols="50"></textarea>
        </div>
        <br>

        <div>
            <label for="NoPostal">Code Postal:</label>
            <input type="text" id="NoPostal" name="NoPostal" pattern="[0-9]{5}" title="Veuillez saisir un code postal valide à 5 chiffres">
            <label for="localite">Localité:</label>
            <input type="text" id="localite" name="localite">
        </div>
        <br>

        <label for="Pays">Pays:</label>
        <div>
            <select name="Pays">
                <option value="">Choisir Votre Pays</option>
                <?php
                $pays = mysqli_query($conn, "SELECT * FROM nationalité");
                if (!$pays) {
                    die("Database query failed: " . mysqli_error($conn));
                }
                while ($row = mysqli_fetch_assoc($pays)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nationalite']; ?></option>
                <?php } ?>
            </select>
        </div>
        <br>

        <div>
            <label for="platforms">Plateforme(s):</label>
            <input type="checkbox" id="windows" name="platforms[]" value="Windows">
            <label for="windows">Windows</label>
            <input type="checkbox" id="mac" name="platforms[]" value="Macintosh">
            <label for="mac">Macintosh</label>
            <input type="checkbox" id="linux" name="platforms[]" value="Linux">
            <label for="linux">Linux</label>
        </div>
        <br>

        <label for="Applications">Application(s):</label>
        <div>
            <select multiple name="Applications[]" id="Applications">
                <option value="Bureautique">Bureautique</option>
                <option value="DAO">DAO</option>
                <option value="Statistiques">Statistiques</option>
                <option value="SGBD">SGBD</option>
                <option value="Internet">Internet</option>
            </select>
        </div>
        <br>

        <label for="sports">Sports:</label>
        <div>
            <select id="sports" multiple name="sports[]">
                <?php
                $sports = mysqli_query($conn, "SELECT * FROM sport");
                if (!$sports) {
                    die("Database query failed: " . mysqli_error($conn));
                }
                while ($sport = mysqli_fetch_assoc($sports)) { ?>
                    <option value="<?php echo $sport['id']; ?>"><?php echo $sport['sport_name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <br>

        <label for="filiere">Filières:</label>
        <div>
            <select name="filiere">
                <option value="">Choisir Votre Filiere</option>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM filieres");
                if (!$result) {
                    die("Database query failed: " . mysqli_error($conn));
                }
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nom_filiere']; ?></option>
                <?php } ?>
            </select>
        </div>
        <br>

        <label for="imageInput">Insérer Image:</label>
        <input type="file" id="imageInput" name="image" accept="image/*">
        <br><br>

        <button type="submit" name="Enregistrer">Enregistrer</button>
        <button type="submit" name="AffichageListe">Affichage Liste</button>
    </form>

    <br>
    <button onclick="uploadImage()">Display Image</button>

    <script src="../script.js"></script>
</body>

</html>