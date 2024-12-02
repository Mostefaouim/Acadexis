<?php
require '../config.php';
session_start();

if (isset($_POST['valider'])) {
    $email =  $_POST['email'];
    $mdp =  $_POST['pass'];
    if (!empty($email) && !empty($mdp)) {
        $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
        $query_ex = mysqli_fetch_assoc($query);

        if ($query && mysqli_num_rows($query) > 0) {

            if ($query_ex['mdp'] == $mdp) {

                $_SESSION['user'] = $query_ex['email'];
                $_SESSION['role'] = $query_ex['role'];

                if ($query_ex['role'] == 'admin') {
                    header('Location: ../index.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                echo "<p style='color:red;'>Mot de passe Ou Email incorrect</p>";
            }
        } else {
            echo "<p style='color:red;'>Le compte n'existe pas</p>";
        }
    } else {
        echo "<p style='color:red;'>Un Champ Est Vide</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h3>Connexion</h3>
    <form action="" method="post">
        <label for="email">Email :</label>
        <input type="email" name="email" id="email"><br><br>
        <label for="pass">Mot de passe :</label>
        <input type="password" name="pass" id="pass"><br><br>
        <button type="submit" name="valider">Valider</button>
        <button type="submit" formaction="./inscri.php">Inscription</button>
    </form>
</body>

</html>