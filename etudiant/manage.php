<?php
require '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

if (isset($_POST['search'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT * FROM personne WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);

    if ($data) {
        $sports_query = "SELECT sport_id FROM personne_sport WHERE personne_id = $id";
        $result_sports = mysqli_query($conn, $sports_query);
        $selected_sports = [];
        while ($row = mysqli_fetch_assoc($result_sports)) {
            $selected_sports[] = $row['sport_id'];
        }
    }
}

if (isset($_POST['supprimer'])) {
    $ID = intval($_POST['id']); 
    $delete_query = "DELETE FROM personne WHERE id = $ID";
    if (mysqli_query($conn, $delete_query)) {
        echo "<p>Enregistrement supprimé avec succès.</p>";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Erreur: " . mysqli_error($conn);
    }
}
if (isset($_POST['modifier'])) {
    $ID = intval($_POST['id']);
    $civility = mysqli_real_escape_string($conn, $_POST['civility']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $NoPostal = mysqli_real_escape_string($conn, $_POST['NoPostal']);
    $localite = mysqli_real_escape_string($conn, $_POST['localite']);
    $pays = mysqli_real_escape_string($conn, $_POST['Pay']);
    $platforms = isset($_POST['platforms']) ? implode(", ", $_POST['platforms']) : "";
    $applications = isset($_POST['applications']) ? implode(", ", $_POST['applications']) : "";
    $filiere = $_POST['filiere'] ?? [];
    $Pay = $_POST['Pay'] ?? [];
    $sql = "SELECT * FROM personne WHERE id = $ID";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);
    $filename = $data['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $filename = uniqid() . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], '../image/' . $filename);
    }
    $update = "UPDATE personne SET 
        Civilité='$civility',
        Nom_pre='$nom',
        email='$email',
        Adresse='$adresse',
        code_post='$NoPostal',
        localite='$localite',
        pays='$pays',
        plat_form='$platforms',
        applications='$applications',
        image='$filename',
        filiere_id='$filiere',
        nationalite_id = '$Pay'
        WHERE id = $ID";

    if (mysqli_query($conn, $update)) {
        if (isset($_POST['sports'])) {
            $sports = $_POST['sports'];
            $delete_sports = "DELETE FROM personne_sport WHERE personne_id = $ID";
            mysqli_query($conn, $delete_sports);
            foreach ($sports as $sport_id) {
                $insert_sport = "INSERT INTO personne_sport (personne_id, sport_id) VALUES ($ID, $sport_id)";
                mysqli_query($conn, $insert_sport);
            }
        }

        echo "<p>Modification effectuée avec succès.</p>";
        header('location: index.php');
    } else {
        echo "Erreur: " . mysqli_error($conn);
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
            <h3 id="h3">Etudiant Numéro: <b><?= isset($data) ? $data['id'] : ''; ?></b></h3>
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
            <input type="radio" id="monsieur" name="civility" value="Monsieur" <?= (isset($data) && $data['Civilité'] == "Monsieur") ? "checked" : ""; ?>>
            <label for="monsieur">Monsieur</label>
            <input type="radio" id="madame" name="civility" value="Madame" <?= (isset($data) && $data['Civilité'] == "Madame") ? "checked" : ""; ?>>
            <label for="madame">Madame</label>
            <input type="radio" id="mademoiselle" name="civility" value="Mademoiselle" <?= (isset($data) && $data['Civilité'] == "Mademoiselle") ? "checked" : ""; ?>>
            <label for="mademoiselle">Mademoiselle</label>
        </div>
        <div style="float: right;">
            <?php
            if (isset($data) && !empty($data['image'])) {
                echo "<img src='../image/" . htmlspecialchars($data['image']) . "' alt='Image' id='imagePreview' style='max-width: 250px;'>";
            } else {
                echo "<p>Aucune image disponible</p>";
            }
            ?>
        </div>
        <br>
        <div>
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="<?= isset($data) ? ($data['email']) : ''; ?>">
        </div>
        <div>
            <label for="nom">Nom/Prenom :</label>
            <input type="text" id="nom" name="nom" value="<?= isset($data) ? ($data['Nom_pre']) : ''; ?>">
        </div>
        <br>
        <label for="adresse">Adresse :</label>
        <div>
            <textarea id="adresse" name="adresse" rows="4" cols="50"><?= isset($data) ? ($data['Adresse']) : ''; ?></textarea>
        </div>
        <br>
        <div>
            <label for="NoPostal">NO Postal/localite :</label>
            <input type="text" id="NoPostal" name="NoPostal" maxlength="5" pattern="[0-9]{5}" title="Veuillez saisir un code postal valide à 5 chiffres" value="<?= isset($data) ? ($data['code_post']) : ''; ?>">
            <input type="text" id="localite" name="localite" value="<?= isset($data) ? ($data['localite']) : ''; ?>">
        </div>
        <br>
        <label for="Pay">Pays:</label>
        <div>
            <select name="Pay">
                <?php
                $pay = mysqli_query($conn, "SELECT * FROM nationalité"); ?>
                <option value="" <?= (empty($data['nationalite_id'])) ? 'selected' : ''; ?>>Choisir Votre Pays</option>
                <?php
                while ($p = mysqli_fetch_assoc($pay)) { ?>
                    <option value="<?= $p['id']; ?>" <?= ($data['nationalite_id'] == $p['id']) ? 'selected' : ''; ?>>
                        <?= $p['nationalite']; // Affiche le nom de la nationalité 
                        ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <br>
        <div>
            <?php $plateformes = isset($data) ? explode(", ", $data['plat_form']) : []; ?>
            <label for="platforms">Plateformes :</label>
            <input type="checkbox" name="platforms[]" value="Windows" <?= (in_array('Windows', $plateformes)) ? 'checked' : ''; ?> /> Windows
            <input type="checkbox" name="platforms[]" value="Linux" <?= (in_array('Linux', $plateformes)) ? 'checked' : ''; ?> /> Linux
            <input type="checkbox" name="platforms[]" value="Macintosh" <?= (in_array('Macintosh', $plateformes)) ? 'checked' : ''; ?> /> Macintosh
        </div>
        <br>
        <label for="Applications">Application(s) :</label>
        <div>
            <?php $applications = isset($data) ? explode(", ", $data['applications']) : []; ?>
            <select id="applications" name="applications[]" multiple>
                <option value="Bureautique" <?= (in_array("Bureautique", $applications)) ? 'selected' : ''; ?>>Bureautique</option>
                <option value="DAO" <?= (in_array("DAO", $applications)) ? 'selected' : ''; ?>>DAO</option>
                <option value="Statistique" <?= (in_array("Statistique", $applications)) ? 'selected' : ''; ?>>Statistique</option>
                <option value="SGBD" <?= (in_array("SGBD", $applications)) ? 'selected' : ''; ?>>SGBD</option>
                <option value="Internet" <?= (in_array("Internet", $applications)) ? 'selected' : ''; ?>>Internet</option>
            </select>
        </div>
        <br>
        <label for="sports">Sports :</label>
        <div>
            <select id="sports" name="sports[]" multiple>
                <?php
                if (isset($data)) {
                    $sports = "SELECT * FROM sport";
                    $spr = mysqli_query($conn, $sports);
                    while ($sport = mysqli_fetch_array($spr)) {
                ?>
                        <option value="<?= $sport['id']; ?>" <?= (in_array($sport['id'], $selected_sports)) ? 'selected' : ''; ?>>
                            <?= $sport['sport_name']; ?>
                        </option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <label for="filiere">Filiers:</label>
        <div>

            <select name="filiere">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM filieres");
                ?>
                <option value="" <?= (empty($data['filiere_id'])) ? 'selected' : ''; ?>></option>
                <?php
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?= $row['id']; ?>" <?= ($data['filiere_id'] == $row['id']) ? 'selected' : ''; ?>><?= $row['nom_filiere']; ?></option>
                <?php } ?>
            </select>
        </div>
        <br>
        <br><label for="imageInput">Insere Image: </label>
        <input type="file" id="imageInput" name="image"><br><br>
        <button type="submit" name="modifier">Modifier</button>
        <button type="submit" name="supprimer" id="deleteButton" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</button>
        <button type="submit" name="AffichageListe" formaction="web.php">Affichage Liste</button>
    </form>
    <button onclick="uploadImage()">Display Image</button><br><br>
    <div id="imageContainer"></div>
    <script src="../script.js"></script>
</body>