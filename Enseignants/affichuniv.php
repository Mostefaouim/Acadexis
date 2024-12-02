<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

require '../config.php';
if (isset($_POST['save'])) {
    $name = $_POST['univ'];
    $univ = mysqli_query($conn, "INSERT INTO universite (nom) VALUES ('$name')");
    if ($univ) {
        header('location : universite.php');
    }
}
if (isset($_POST['affiche'])) {
    $result = mysqli_query($conn, "SELECT *FROM  universite"); ?>
    <center>
        <table border="1" style="text-align: center;">
            <tr>
                <th>#Id</th>
                <th>Nom</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nom']; ?></td>
            <?php }
        }
            ?>