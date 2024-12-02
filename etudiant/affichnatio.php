<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: connect.php");
    exit();
}

require '../config.php';
if (isset($_POST['display'])) {
    $sql = "SELECT * FROM nationalité";
    $data = mysqli_query($conn, $sql);
?>

    <center>
        <h2>List des nationalite</h2><br>
        <table border="1" style="text-align: center;">
            <tr>
                <!-- <th>Id</th> -->
                <th>#Id</th>
                <th>Code</th>
                <th>Nationalite</th>
            </tr>
            <?php

            while ($display = mysqli_fetch_array($data)) { ?>

                <tr>
                    <!-- <td><?php //echo $display['id']; 
                                ?></td> -->
                    <td><?= $display['id']; ?></td>
                    <td><?= $display['code']; ?></td>
                    <td><?= $display['nationalite']; ?></td>
            <?php }
        } ?>
            <br>
                </tr>

        </table>
    </center>