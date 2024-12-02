<?php
require '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}
if (isset($_POST['search'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT * FROM modules WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);
}

if (isset($_POST['supprimer'])) {
    $ID = intval($_POST['id']);
    $delete_query = "DELETE FROM modules WHERE id = $ID";
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
    $code_module = $_POST['code_module'];
    $designation_module = $_POST['designation_module'];
    $coefficient = $_POST['coefficient'];
    $volume_horaire = $_POST['volume_horaire'];
    $filiere_id = $_POST['filiere_id'];

    $sql = "SELECT * FROM modules WHERE id = $ID";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);


    $update = "UPDATE modules SET 
        code_module ='$code_module',
        designation_module ='$designation_module',
        coefficient ='$coefficient',
        volume_horaire ='$volume_horaire',
        filiere_id ='$filiere_id'
        WHERE id=$ID";

    if (mysqli_query($conn, $update)) {
        echo "<p>Modules modifié avec succès.</p>";
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
            <h3 id="h3">Modules Numéro: <b><?= isset($data) ? $data['id'] : ''; ?></b></h3>
        </center>
    </div>
    <form method="post" enctype="multipart/form-data" id="studentForm">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : ''; ?>">
        <div>
            <label for="code_module">Code Module :</label>
            <input type="text" name="code_module" value="<?= $data['code_module'] ?>">
        </div><br>
        <div>
            <label for="designation_module">Désignation :</label>
            <input type="text" name="designation_module" value="<?= $data['designation_module'] ?>">
        </div><br>
        <div>
            <label for="coefficient">Coefficient :</label>
            <input type="number" step="0.01" name="coefficient" value="<?= $data['coefficient'] ?>">
        </div><br>
        <div>
            <label for="volume_horaire">Volume Horaire :</label>
            <input type="number" name="volume_horaire" value="<?= $data['volume_horaire'] ?>">
        </div><br>
        <div>
            <label for="filiere_id">Filière :</label>
            <select name="filiere_id">
                <option value="" <?= (empty($data['filiere_id'])) ? 'selected' : ''; ?>>Choisir Votre Filiere</option>
                <?php $filieres = mysqli_query($conn, "SELECT * FROM filieres");
                while ($row = mysqli_fetch_assoc($filieres)) { ?>
                    <option value="<?= $row['id']; ?>" <?= ($data['filiere_id'] == $row['id']) ? 'selected' : ''; ?>><?= $row['nom_filiere']; ?></option>
                <?php } ?>
            </select>
        </div><br>

        <button type="submit" name="modifier">Modifier</button>
        <button type="submit" name="supprimer" id="deleteButton" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</button>
        <button type="submit" name="afficheList" formaction="affichModl.php">Affichage Liste</button>
    </form>

    <script src="../script.js"></script>
</body>