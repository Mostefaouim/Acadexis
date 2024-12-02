
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: connect.php");
    exit();
}

require '../config.php';
if (isset($_POST['affiche'])) {
    $result = mysqli_query($conn, "SELECT * FROM filieres"); ?>

    <center>
        <h2>Liste des filières</h2>
        <table border="1" style="text-align: center;">
            <tr>
                <th>#Id</th>
                <th>Nom</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nom_filiere']; ?></td>
            <?php }
        }
            ?>