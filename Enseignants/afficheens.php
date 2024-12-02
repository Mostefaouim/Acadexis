<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

require_once '../config.php';
if (isset($_POST['afficheList'])) {
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM enseignants ";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<center><h1>Tous Les Enseinants</h1></center>";
?>
        <center>
            <table border="1" style="text-align: center;">
                <tr>
                    <th>#Id</th>
                    <th>Numero</th>
                    <th>Civilité</th>
                    <th>Nom</th> 
                    <th>Email</th> 
                    <th>Adresse</th>
                    <th>Date de Naissance</th>
                    <th>Lieu de Naissance</th>
                    <th>Pays</th>
                    <th>Universite</th>
                    <th>Grade</th>
                    <th>Spécialité</th>

                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['numero']; ?></td>
                        <td><?= $row['civilite']; ?></td>
                        <td><?= $row['nom_prenom']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['adresse']; ?></td>
                        <td><?= $row['date_naissance']; ?></td>
                        <td><?= $row['lieu_naissance']; ?></td>
                        <td>
                            <?php
                            $result2 = mysqli_query($conn, "SELECT * FROM nationalité");
                            $nationalite = [];
                            while ($data = mysqli_fetch_assoc($result2)) {
                                $nationalite[$data['id']] = $data['nationalite'];
                            }
                            if (array_key_exists($row['nationalite_id'], $nationalite)) {
                                echo htmlspecialchars($nationalite[$row['nationalite_id']]);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $result3 = mysqli_query($conn, "SELECT * FROM universite");
                            $univ = [];
                            while ($data1 = mysqli_fetch_assoc($result3)) {
                                $univ[$data1['id']] = $data1['nom'];
                            }
                            if (array_key_exists($row['univ_id'], $univ)) {
                                echo $univ[$row['univ_id']];
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>

                        <td><?= $row['grade']; ?></td>
                        <td><?= $row['specialite']; ?></td>

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
