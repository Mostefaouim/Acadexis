<?php
require '../config.php';

if (isset($_POST['search'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT * FROM enseignants WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);
}

if (isset($_POST['supprimer'])) {
    $ID = intval($_POST['id']);
    $delete_query = "DELETE FROM enseignants WHERE id = $ID";
    if (mysqli_query($conn, $delete_query)) {
        echo "<p>Enregistrement supprimé avec succès.</p>";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Erreur: " . mysqli_error($conn);
    }
}

if (isset($_POST['modifier'])) {
    $numero = mysqli_real_escape_string($conn, $_POST['numero']);
    $civilite = mysqli_real_escape_string($conn, $_POST['civility'] ?? '');
    $nom_prenom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $date_naissance = mysqli_real_escape_string($conn, $_POST['date_naissance']);
    $lieu_naissance = mysqli_real_escape_string($conn, $_POST['lieu_naissance']);
    $pays = mysqli_real_escape_string($conn, $_POST['Pay'] ?? null);
    $univ = mysqli_real_escape_string($conn, $_POST['univ'] ?? null);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $specialite = mysqli_real_escape_string($conn, $_POST['specialite']);
    $ID = intval($_POST['id']);

    $sql = "SELECT * FROM enseignants WHERE id = $ID";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);


    $update = "UPDATE enseignants SET 
        numero='$numero',
        civilite='$civilite',
        nom_prenom='$nom_prenom',
        email='$email',
        adresse='$adresse',
        date_naissance='$date_naissance',
        lieu_naissance='$lieu_naissance',
        nationalite_id='$pays',
        grade='$grade',
        specialite='$specialite',
        univ_id = '$univ'
        WHERE id=$ID";

    if (mysqli_query($conn, $update)) {
        echo "<p>Enregistrement modifié avec succès.</p>";
        header('Location: index.php');
        exit();
    } else {
        echo "Erreur de mise à jour : " . mysqli_error($conn);
    }
}
?>

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

<body>
    <div>
        <center>
            <h3 id="h3">Enseignant Numéro: <b><?= isset($data) ? $data['id'] : ''; ?></b></h3>
        </center>
    </div>
    <form method="post" enctype="multipart/form-data" id="studentForm">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : ''; ?>">
        <div>
            <label for="numero">Numéro :</label>
            <input type="text" id="numero" name="numero" value="<?= isset($data) ? $id : ''; ?>">
        </div>
        <br>
        <div>
            <label for="civility">Civilité :</label>
            <input type="radio" id="monsieur" name="civility" value="Monsieur" <?= (isset($data) && $data['civilite'] == "Monsieur") ? "checked" : ""; ?>>
            <label for="monsieur">Monsieur</label>
            <input type="radio" id="madame" name="civility" value="Madame" <?= (isset($data) && $data['civilite'] == "Madame") ? "checked" : ""; ?>>
            <label for="madame">Madame</label>
            <input type="radio" id="mademoiselle" name="civility" value="Mademoiselle" <?= (isset($data) && $data['civilite'] == "Mademoiselle") ? "checked" : ""; ?>>
            <label for="mademoiselle">Mademoiselle</label>
        </div>

        <br>
        <div>
            <label for="nom">Nom/Prenom :</label>
            <input type="text" id="nom" name="nom" value="<?= isset($data) ? ($data['nom_prenom']) : ''; ?>">
        </div>
        <div>
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="<?= isset($data) ? ($data['email']) : ''; ?>">
        </div>
        <br>
        <label for="adresse">Adresse :</label>
        <div>
            <textarea id="adresse" name="adresse" rows="4" cols="50"><?= isset($data) ? ($data['adresse']) : ''; ?></textarea>
        </div>
        <br>
        <div>
            <label for="date_naissance">Date de Naissance :</label>
            <input type="date" name="date_naissance" value="<?= isset($data) ? ($data['date_naissance']) : ''; ?>">
        </div><br>
        <div>
            <label for="lieu_naissance">Lieu de Naissance :</label>
            <input type="text" name="lieu_naissance" value="<?= isset($data) ? ($data['lieu_naissance']) : ''; ?>">
        </div><br>
        <label for="Pay">Pays:</label>
        <div>
            <select name="Pay">
                <?php
                $pay = mysqli_query($conn, "SELECT * FROM nationalité"); ?>
                <option value="" <?= (empty($data['nationalite_id'])) ? 'selected' : ''; ?>>Choisir Votre Pays</option>
                <?php
                while ($p = mysqli_fetch_assoc($pay)) { ?>
                    <option value="<?= $p['id']; ?>" <?= ($data['nationalite_id'] == $p['id']) ? 'selected' : ''; ?>>
                        <?= $p['nationalite'];
                        ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <br>
        <label for="univ">Universite:</label>
        <div>
            <select name="univ">
                <?php
                $univ = mysqli_query($conn, "SELECT * FROM universite"); ?>
                <option value="" <?= (empty($data['univ_id'])) ? 'selected' : ''; ?>>Choisir Votre Pays</option>
                <?php
                while ($u = mysqli_fetch_assoc($univ)) { ?>
                    <option value="<?= $u['id']; ?>" <?= ($data['univ_id'] == $u['id']) ? 'selected' : ''; ?>>
                        <?= $u['nom'];
                        ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <br>
        <div>
            <label for="grade">Grade :</label>
            <?php $grade = isset($data) ? explode(", ", $data['grade']) : []; ?>
            <select name="grade">
                <option value="" <?= (empty($data['specialite'])) ? 'selected' : ''; ?>>Choisir Votre Grad</option>
                <option value="Assistant" <?= (in_array("Assistant", $grade)) ? 'selected' : ''; ?>>Assistant</option>
                <option value="MAB" <?= (in_array("MAB", $grade)) ? 'selected' : ''; ?>>MAB</option>
                <option value="MAA" <?= (in_array("MAA", $grade)) ? 'selected' : ''; ?>>MAA</option>
                <option value="MCB" <?= (in_array("MCB", $grade)) ? 'selected' : ''; ?>>MCB</option>
                <option value="MCA" <?= (in_array("MCA", $grade)) ? 'selected' : ''; ?>>MCA</option>
                <option value="Professeur" <?= (in_array("Professeur", $grade)) ? 'selected' : ''; ?>>Professeur</option>
            </select>
        </div><br>
        <div>
            <label for="specialite">Spécialité :</label>
            <?php $specialite = isset($data) ? explode(", ", $data['specialite']) : []; ?>
            <select name="specialite">
                <option value="" <?= (empty($data['specialite'])) ? 'selected' : ''; ?>>Choisir Votre Pays</option>
                <option value="Informatique" <?= (in_array("Informatique", $specialite)) ? 'selected' : ''; ?>>Informatique</option>
                <option value="Mathématiques" <?= (in_array("Mathématiques", $specialite)) ? 'selected' : ''; ?>>Mathématiques</option>
                <option value="Anglais" <?= (in_array("Anglais", $specialite)) ? 'selected' : ''; ?>>Anglais</option>
                <option value="Autres" <?= (in_array("Autres", $specialite)) ? 'selected' : ''; ?>>Autres</option>
            </select>
        </div>
        <br>

        <button type="submit" name="modifier">Modifier</button>
        <button type="submit" name="supprimer" id="deleteButton" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</button>
        <button type="submit" name="afficheList" formaction="afficheens.php">Affichage Liste</button>
    </form>
    <div id="imageContainer"></div>
    <script src="../script.js"></script>
</body>