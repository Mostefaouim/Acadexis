<?php
require '../config.php';
if (isset($_POST['save'])) {
    $name = $_POST['sport'];
    mysqli_query($conn, "INSERT INTO sport (sport_name) VALUES ('$name')") or die(mysqli_error($conn));
}
if (isset($_POST['affiche'])) {
    $result = mysqli_query($conn, "SELECT *FROM  sport"); ?>
    <center>
        <table border="1" style="text-align: center;">
            <tr>
                <th>#Id</th>
                <th>Nom</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['sport_name']; ?></td>
            <?php }
        }
            ?>