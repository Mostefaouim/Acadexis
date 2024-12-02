<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

$filiere = $_POST['filiere'] ?? [];
$query = mysqli_query($conn, "SELECT nom_filiere FROM filieres WHERE id = $filiere");
$query = mysqli_fetch_assoc($query);
?>
<title>Pv Globale de <?= $query['nom_filiere'] ?></title>
<center>
    <h1>Filiere : <?= $query['nom_filiere'] ?></h1><br>
    <h1>PV GLOBAL</h1><br>
    <div>
        <table border="2" style="text-align: center; margin-bottom: 20px;">
            <tr>
                <th>#Id Etudiant</th>
                <th>Nom / Prenom Etudiant</th>
                <th>Moyenne</th>
            </tr>
            <?php
            $myn = 0;
            $etudiant_query = mysqli_query($conn, "SELECT * FROM personne WHERE filiere_id = $filiere");

            while ($etu_data = mysqli_fetch_assoc($etudiant_query)) {
                $idetu = $etu_data['id'];
                $nom = $etu_data['Nom_pre'];
                $somme = mysqli_query($conn, "SELECT SUM(coefficient * note) / SUM(coefficient) AS moyenne 
                                              FROM notes 
                                              WHERE num_Etudiant = $idetu");
                $somme = mysqli_fetch_assoc($somme);
                $moyenne = floor($somme['moyenne'] * 100) / 100;
            ?>
                <tr>
                    <td><?= $idetu ?></td>
                    <td><?= $etu_data['Nom_pre'] ?></td>
                    <td style="display: none;"><?= $etu_data['email'] ?></td>
                    <td><?= $moyenne ?></td>
                </tr>
            <?php
                $check_existing = mysqli_query($conn, "SELECT * FROM pv WHERE id_etu = '$idetu' AND filier = '$filiere'");

                if (mysqli_num_rows($check_existing) == 0) {
                    $insert_pv = mysqli_query($conn, "INSERT INTO pv (id_etu, nom_prenom, moyenne_generale, filier) 
                                                      VALUES ('$idetu', '$nom', '$moyenne', '$filiere')");
                } else {
                    $update_pv = mysqli_query($conn, "UPDATE pv SET moyenne_generale = '$moyenne' 
                                                      WHERE id_etu = '$idetu' AND filier = '$filiere'");
                }
                $myn += $moyenne;
            }
            $total_etudiants = mysqli_num_rows($etudiant_query);
            $moyenne_globale = $total_etudiants > 0 ? floor(($myn / $total_etudiants) * 100) / 100 : 0;
            $somme_stats = mysqli_query($conn, "SELECT MIN(moyenne) AS min, MAX(moyenne) AS max, AVG(moyenne) AS avg FROM (
                SELECT SUM(coefficient * note) / SUM(coefficient) AS moyenne 
                FROM notes 
                WHERE num_Etudiant IN (SELECT id FROM personne WHERE filiere_id = $filiere)
                GROUP BY num_Etudiant
            ) AS moyennes");
            $somme_stats = mysqli_fetch_assoc($somme_stats);
            ?>
            <tr>
                <td colspan="2"><strong>Moyenne Globale</strong></td>
                <td colspan="2"><strong><?= $moyenne_globale ?></strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Moyenne Max</strong></td>
                <td colspan="2"><strong><?= floor($somme_stats['max'] * 100) / 100 ?></strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Moyenne Min</strong></td>
                <td colspan="2"><strong><?= floor($somme_stats['min'] * 100) / 100 ?></strong></td>
            </tr>
        </table>
        <button type="submit" id="print" onclick="printForm()">Imprimer</button>
        <br>
        <form method="post">
            <input type="hidden" name="filiere" value="<?= $filiere ?>">
            <br><button type="submit" id="stat" formaction="../PV/stat.php">Voir Static</button>
            <button type="submit" id="stat" formaction="envoyer.php">Envoyer Email</button>
        </form>
    </div>
</center>

<script>
    function printForm() {
        let printButton = document.getElementById("print");
        let stat = document.getElementById("stat");
        printButton.style.display = "none";
        stat.style.display = "none";
        window.print();
    }
</script>