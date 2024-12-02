<?php
ob_start();
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

if (isset($_POST['rechercher'])) {
    $num = $_POST['num'];
    if (empty($num)) {
        echo "<font color='red'>Entrer Le Numero D'etudiant <font>";
    }
    $sql = "SELECT * FROM personne WHERE id = $num";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $etudiant = mysqli_fetch_assoc($result);
        $filiere_id = $etudiant['filiere_id'];
        $modules_sql = "SELECT * FROM modules WHERE filiere_id = $filiere_id ";
        $modules_result = mysqli_query($conn, $modules_sql);
        $code_module = '';
        $coefficient = '';
        $note_value = '';
        if (isset($_POST['module']) && $_POST['module'] != '') {
            $module_id = $_POST['module'];
            $module_query = "SELECT coefficient, code_module FROM modules WHERE id = $module_id";
            $module_result = mysqli_query($conn, $module_query);
            if ($module_data = mysqli_fetch_assoc($module_result)) {
                $code_module = $module_data['code_module'];
                $coefficient = $module_data['coefficient'];
            }
            $note_query = "SELECT note FROM Notes WHERE num_Etudiant = $num AND nom_module = $module_id";
            $note_result = mysqli_query($conn, $note_query);
            if ($note_data = mysqli_fetch_assoc($note_result)) {
                $note_value = $note_data['note'];
            }
        }
?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <title>Gestion des Note</title>
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
            <!-- <div>
                <center>
                    <h3>Étudiant Numéro: <b><?= $num ?></b></h3>
                </center>
            </div> -->
            <center>
                <form action="manage.php" method="post">
                    <label for="num">Recharche Etudiants</label>
                    <input type="number" name="num" id="num" value="<?= $num ?>">
                    <button type="submit" name="rechercher">Recharche</button>
                </form>
            </center><br>
            <form method="POST">
                <input type="hidden" name="num" value="<?= $num; ?>">
                <input type="hidden" name="rechercher" value="1">
                <br>
                <div>
                    <label for="civility">Civilité :</label>
                    <input type="text" name="civility" id="civility" value="<?= $etudiant['Civilité'] ?? ""; ?>" readonly>
                </div>
                <div style="float: right;">
                    <?php
                    if (isset($etudiant) && !empty($etudiant['image'])) {
                        echo "<img src='../image/" . htmlspecialchars($etudiant['image']) . "' alt='Image' id='imagePreview' style='max-width: 250px;'>";
                    } else {
                        echo "<p>Aucune image disponible</p>";
                    }
                    ?>
                </div>
                <br>
                <div>
                    <label for="nom">Nom/Prénom :</label>
                    <input type="text" id="nom" name="nom" value="<?= $etudiant['Nom_pre']; ?>">
                </div><br>
                <div>
                    <label for="filiere">Filière :</label>
                    <select name="filiere" id="filiere" readonly>
                        <?php
                        $filieres_result = mysqli_query($conn, "SELECT * FROM filieres");
                        while ($row = mysqli_fetch_assoc($filieres_result)) { ?>
                            <option value="<?= $row['id']; ?>" <?= ($etudiant['filiere_id'] == $row['id']) ? 'selected' : ''; ?>><?= $row['nom_filiere']; ?></option>
                        <?php } ?>
                    </select>
                </div><br>
                <div>
                    <label for="module">Module :</label>
                    <select name="module" id="module" onchange="this.form.submit()">
                        <option value="">Choisir un Module</option>
                        <?php
                        // mysqli_data_seek($modules_result, 0); // Remettre le pointeur au début
                        while ($module = mysqli_fetch_assoc($modules_result)) {
                            $selected = (isset($_POST['module']) && $_POST['module'] == $module['id']) ? 'selected' : ''; ?>
                            <option value="<?= $module['id']; ?>" <?= $selected; ?>><?= $module['nom']; ?></option>
                        <?php } ?>
                    </select>
                </div><br>
                <div>
                    <label for="code_module">Code Module :</label>
                    <input type="text" id="code_module" name="code_module" value="<?= $code_module; ?>" readonly>
                </div><br>
                <div>
                    <label for="coefficient">Coefficient :</label>
                    <input type="text" id="coefficient" name="coefficient" value="<?= $coefficient; ?>" readonly>
                </div><br>
                <div>
                    <label for="note">Note :</label>
                    <input type="text" id="note" name="note" value="<?= $note_value; ?>">
                </div><br>
                <button type="submit" name="enregistrer">Enregistrer</button>
                <button type="submit" name="modifier">Modifier</button>
                <button type="submit" name="supprimer">Supprimer</button>
                <button type="submit" name="AffichageListe" formaction="display.php">Affichage Liste</button>
                <button type="submit" name="bulletin" formaction="bulletin.php">Relever De Note</button>
            </form>
        </body>

        </html>
<?php
    } else {
        echo "<font color='red'>Étudiant Inexistant<font>";
    }
}
if (isset($_POST['enregistrer'])) {

    $num_etudiant = $_POST['num'];
    $filiere = $etudiant['filiere_id'];
    $nom_module = $_POST['module'];
    $code_module = $_POST['code_module'];
    $coefficient = $_POST['coefficient'];
    $note = $_POST['note'];
    if (!empty($nom_module) && !empty($coefficient) && !empty($code_module) && !empty($note)) {
        $check_note_sql = "SELECT * FROM Notes WHERE num_Etudiant = $num_etudiant AND nom_module = $nom_module";
        $check_note_result = mysqli_query($conn, $check_note_sql);
        if (mysqli_num_rows($check_note_result) > 0) {
            echo "<br><br><font color = 'red'>La note pour ce module existe déjà.<font>";
        } else {
            $save_sql = "INSERT INTO Notes (num_Etudiant, filiere, nom_module, code_module, coefficient, note)
            VALUES ('$num_etudiant', '$filiere', '$nom_module', '$code_module', '$coefficient', '$note')";

            $save_result = mysqli_query($conn, $save_sql);
            if ($save_result) {
                echo "Note enregistrée avec succès.";
                //header('location: index.php');
            } else {
                echo "Erreur lors de l'enregistrement de la note.";
            }
        }
    } else {
        echo "<br><center><font color = 'red'>Un Champ est Vide</font><center>";
    }
}
if (isset($_POST['modifier'])) {
    $num_etudiant = $_POST['num'];
    $filiere = $etudiant['filiere_id'];
    $nom_module = $_POST['module'];
    $code_module = $_POST['code_module'];
    $coefficient = $_POST['coefficient'];
    $note = $_POST['note'];
    if (!empty($note)) {
        $check_note_sql = "SELECT * FROM Notes WHERE num_Etudiant = $num_etudiant AND nom_module = '$nom_module'";
        $check_note_result = mysqli_query($conn, $check_note_sql);
        if (mysqli_num_rows($check_note_result) > 0) {
            $update_sql = "UPDATE Notes SET
                        filiere = '$filiere',
                        code_module = '$code_module',
                        -- coefficient = $coefficient,
                        note = $note
                        WHERE num_Etudiant = $num_etudiant AND nom_module = '$nom_module'";
            $update_result = mysqli_query($conn, $update_sql);
            if ($update_result) {
                echo "<br><br>Note mise à jour avec succès.";
                header('location: index.php');
            } else {
                echo "Erreur lors de la mise à jour de la note.";
            }
        } else {
            echo "La note pour ce module n'existe pas encore.";
        }
    } else {
        echo "<br><center><font color = 'red'>Un Champ est Vide</font><center>";
    }
}
if (isset($_POST['supprimer'])) {
    $num_etudiant = $_POST['num'];
    $nom_module = $_POST['module'];
    $check_note_sql = "SELECT * FROM Notes WHERE num_Etudiant = $num_etudiant AND nom_module = '$nom_module'";
    $check_note_result = mysqli_query($conn, $check_note_sql);
    if (mysqli_num_rows($check_note_result) > 0) {
        $delete_sql = "DELETE FROM Notes WHERE num_Etudiant = $num_etudiant AND nom_module = '$nom_module'";
        $delete_result = mysqli_query($conn, $delete_sql);
        if ($delete_result) {
            echo "Suppression réussie.";
            header('location: index.php');
        } else {
            echo "Erreur lors de la suppression de la note.";
        }
    } else {
        echo "La note pour ce module n'existe pas encore.";
    }
}
ob_end_flush()
?>