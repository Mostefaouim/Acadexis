<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

$id = $_POST['num'] ?? null;
$resulta = mysqli_query($conn, "SELECT * FROM personne");
$filiere_result = mysqli_query($conn, "SELECT * FROM filieres");
$filieres = [];
while ($data = mysqli_fetch_assoc($filiere_result)) {
    $filieres[$data['id']] = $data['nom_filiere'];
}
?>
<center>
    <table border="2" style="text-align: center;">
        <tr>
            <th>#Id Etudiant</th>
            <th>Nom</th>
            <th>Filiere</th>
            <th>Module</th>
            <th>Coefficient</th>
            <th>Code Module</th>
            <th>Note</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($resulta)) {
            $idetu = $row['id'];
            $module_query = mysqli_query($conn, "SELECT * FROM notes WHERE num_Etudiant = $idetu ");
            while ($mod_data = mysqli_fetch_assoc($module_query)) {
                $module_id = $mod_data['nom_module'] ?? null;
                $note = $mod_data['note'] ?? '--';
                $coef = $mod_data['coefficient'] ?? '--';
                $code = $mod_data['code_module'] ?? '--';
                if ($module_id) {
                    $module_name_query = mysqli_query($conn, "SELECT nom FROM modules WHERE id = $module_id");
                    $module_result = mysqli_fetch_assoc($module_name_query);
                    $module_name = $module_result['nom'] ?? '--';
                }
        ?>
                <tr>
                    <?php ?>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['Nom_pre']; ?></td>
                    <td><?= $filieres[$row['filiere_id']] ?? '--'; ?></td>
                    <td><?= $module_name; ?></td>
                    <td><?= $coef; ?></td>
                    <td><?= $code; ?></td>
                    <td><?= $note; ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </table>

</center>