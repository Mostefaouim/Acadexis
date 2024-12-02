<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

require_once '../config.php';
if (isset($_POST['afficheList'])) {


    $sql = "SELECT * FROM modules ";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<center><h1>Tous Les Module</h1></center>";
?>
        <center>
            <table border="1" style="text-align: center;">
                <tr>
                    <th>#Id</th>
                    <th>Nom De Module</th>
                    <th>Code De Module</th>
                    <th>Designation</th>
                    <th>Coefficient</th>
                    <th>Volume Horaire</th>
                    <th>Filiere</th>

                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nom']; ?></td>
                        <td><?= $row['code_module']; ?></td>
                        <td><?= $row['designation_module']; ?></td>
                        <td><?= $row['coefficient']; ?></td>
                        <td><?= $row['volume_horaire']; ?></td>
                        <td>
                            <?php
                            $result1 = mysqli_query($conn, "SELECT * FROM filieres");


                            $filieres = [];
                            while ($data = mysqli_fetch_assoc($result1)) {
                                $filieres[$data['id']] = $data['nom_filiere'];
                            }

                            if (array_key_exists($row['filiere_id'], $filieres)) {
                                echo $filieres[$row['filiere_id']];
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br><br><a href="index.php" style="text-decoration: none; color:black;">Menu Principale</a>
        </center>
<?php
    } else {
        echo "0 résultats.";
    }
}
