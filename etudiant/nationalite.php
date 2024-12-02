<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}


require '../config.php';
if (isset($_POST['save'])) {
    $code = $_POST['code'];
    $libelle = $_POST['libelle'];

    if (empty($code) || empty($libelle)) {
        echo "<p style='color:red'>Verifier Votre Information.</p>";
    } else {
        $save = "INSERT INTO nationalité (code, nationalite) VALUES ('$code', '$libelle')";
        $query = mysqli_query($conn, $save);
        echo "<p style='color:green'>Nationalite Ajoutee avec Succes.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fourmulaire de Nationalite</title>
</head>

<body>

    <h2>Ajouter Une Nationalite</h2>
    <form method="post">
        <label for="code">Code:</label>
        <input type="text" id="code" name="code"><br>
        <br><label for="libelle">Libelle:</label>
        <input type="text" id="libelle" name="libelle"><br>

        <br><button type="submit" name="save">Enregitrer</button>
        <button type="submit" name="display" formaction="affichnatio.php">Afficher</button>
    </form>

    <script>
        let paragraphs = document.getElementsByTagName('p');

        setTimeout(() => {
            for (let i = 0; i < paragraphs.length; i++) {
                paragraphs[i].style.display = 'none'; // Hides each <p> element
            }
        }, 3000);
    </script>
</body>

</html>