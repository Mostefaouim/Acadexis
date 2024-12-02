<style>
    a {
        text-decoration: none;
    }

    .edit {
        color: green;
    }

    .delete {
        color: red;
    }
</style>
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: connect.php");
    exit();
}
?>

<title>List D'utilisateur</title>
<?php
require '../config.php';
$query = mysqli_query($conn, "SELECT * FROM user");
?><center>
    <br>
    <h3>List D'utilisateur</h3>
    <table border="1" style="text-align: center;">
        <tr>
            <th>#Id Utilisateur</th>
            <th>email</th>
            <th>Role</th>
            <th>Mote De Pass</th>
            <th>Action</th>

        </tr>
        <?php while ($row = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <?php ?>
                <td><?= $row['id']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['role'] ?></td>
                <td><?= $row['mdp'] ?></td>
                <td><a class="edit" href="edit.php?id=<?= $row['id'] ?>">Modify</a>
                    <a class="delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Voullez Vous Supprimer L\'utilisateur')">Delete</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br><a href="graph.php" style="color: black;">Users Statistiques</a>

</center>