<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}


?>

<form action="../Enseignants/dispalyPv.php" method="post">
    <div>
        <label for="filiere">Filière :</label>
        <select name="filiere" id="filiere">
            <?php
            $filieres_result = mysqli_query($conn, "SELECT * FROM filieres");
            while ($row = mysqli_fetch_assoc($filieres_result)) { ?>
                <option value="<?= ($row['id']); ?>" <?= (isset($etudiant['filiere_id']) && $etudiant['filiere_id'] == $row['id']) ? 'selected' : ''; ?>>
                    <?= ($row['nom_filiere']); ?>
                </option>
            <?php } ?>
        </select>
    </div><br>
    <button type="submit">Voir Le Pv Global</button>
    <!-- <button type="submit" formaction="stat.php">Statistiques </button> -->
</form>