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
        a {
            text-decoration: none;
            color: black;
            margin: 10px;
            display: inline-block;
            padding: 10px 15px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        a:hover {
            background-color: #e0e0e0;
        }

        .menu {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin: 25px;
        }
    </style>
</head>

<body>
    <div class="menu">
        <a href="etudiant/index.php" title="Manage Students">Etudiants</a>
        <a href="Enseignants/" title="Manage Teachers">Enseignants</a>
        <a href="Modules/index.php" title="Manage Modules">Modules</a>
        <a href="Bulletin/index.php" title="View Bulletins">Bulletin de Note</a>
        <a href="Bulletin/pv.php" title="View PV">PV</a>
        <a href="Static/statistiques.php" title="View Statistics">Statistiques</a>
        <a href="PV/pv.php" title="View PV Statistics">PV Statistiques</a>
        <a href="users/display.php" title="User List">Liste D'utilisateur</a>
        <a href="users/graph.php" title="User Statistics">Users Statistiques</a>
        <a href="table.php" title="View Tables">Tables</a>
    </div>
    <center><a href="users/logout.php" style="margin-top: 20px;">Deconnecter</a></center>
</body>

</html>