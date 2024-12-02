<?php
require '../config.php';
if (isset($_POST['valider'])) {
    $email = $_POST['email'];
    $mdp = $_POST['pass'];
    $role = $_POST['role'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
    $query_ex = mysqli_fetch_assoc($query);
    if (mysqli_num_rows($query) > 0) {
        echo "<p style='color:red;'>email deja exister <p>";
    } else {
        if (!empty($email) && !empty($mdp) && !empty($role)) {
            $insert_query = mysqli_query($conn, "INSERT INTO user (email, mdp, role) VALUES ('$email', '$mdp', '$role')");
            if ($role == 'admin') {
                header('location:../index.php');
            } else {
                header('location:index.php');
            }
        } else {
            echo "<p style='color:red;'>Un Champ est Vide!<p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>
    <h3>Inscription</h3>
    <form action="" method="post">
        <input type="email" name="email" id="email"><br><br>
        <input type="password" name="pass" id="pass"><br><br>
        <select name="role" id="role">
            <option value="">Selctionner Votre Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br><br>
        <button type="submit" name="valider">Valider</button>
        <br>
    </form>
</body>

</html>