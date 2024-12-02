<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: users/connect.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Menu</title>
    <style>
        a{
            text-decoration: none;
            color: black;
            margin-top: 20px;
        }
        form{
            margin: 25px;
            margin-left: 12px;
        }
    </style>
</head>

<body>
    <form method="post">
        <button type="submit" formaction="etudiant/index.php" title="Manage Students">Etudiants</button>
        <button type="submit" formaction="Enseignants/" title="Manage Teachers">Enseignants</button>
        <button type="submit" formaction="Modules/index.php" title="Manage Modules">Modules</button>
        <button type="submit" formaction="Bulletin/index.php" title="View Bulletins">Bulletin de Note</button>
        <button type="submit" formaction="Bulletin/pv.php" title="View PV">PV</button>
        <button type="submit" formaction="Static/statistiques.php" title="View Statistics">Statistiques</button>
        <button type="submit" formaction="PV/pv.php" title="View PV Statistics">PV Statistiques</button>
        <button type="submit" formaction="users/display.php" title="User List">Liste D'utilisateur</button>
        <button type="submit" formaction="users/graph.php" title="User Statistics">Users Statistiques</button>
        <button type="submit" formaction="table.php" title="View Tables">Tables</button>
    </form><br>
    <center><a href="users/logout.php">Deconnecter</a></center>
</body>

</html>
