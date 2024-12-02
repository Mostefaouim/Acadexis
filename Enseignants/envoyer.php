<?php
require_once '../config.php';
require '../vendor/autoload.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

$filiere = isset($_POST['filiere']) ? intval($_POST['filiere']) : 0;

if ($filiere === 0) {
    echo "Filière non spécifiée.";
    exit();
}

$filiere_query = mysqli_query($conn, "SELECT nom_filiere FROM filieres WHERE id = $filiere");
$filiere_data = mysqli_fetch_assoc($filiere_query);
$nom_filiere = $filiere_data['nom_filiere'] ?? 'Inconnu';

$etudiant_query = mysqli_query($conn, "SELECT id, email, Nom_pre FROM personne WHERE filiere_id = $filiere");
$etudiants = [];
while ($row = mysqli_fetch_assoc($etudiant_query)) {
    $etudiants[] = $row;
}

if (empty($etudiants)) {
    echo "Aucun étudiant trouvé pour cette filière.";
    exit();
}

$myn = 0;
$etudiants_data = [];
foreach ($etudiants as $etu) {
    $idetu = $etu['id'];
    $nom = $etu['Nom_pre'];

    $somme_query = mysqli_query($conn, "SELECT SUM(coefficient * note) / SUM(coefficient) AS moyenne 
                                        FROM notes 
                                        WHERE num_Etudiant = $idetu");
    $somme = mysqli_fetch_assoc($somme_query);
    $moyenne = floor(($somme['moyenne'] ?? 0) * 100) / 100;

    $check_existing = mysqli_query($conn, "SELECT * FROM pv WHERE id_etu = '$idetu' AND filier = '$filiere'");
    if (mysqli_num_rows($check_existing) == 0) {
        mysqli_query($conn, "INSERT INTO pv (id_etu, nom_prenom, moyenne_generale, filier) 
                             VALUES ('$idetu', '$nom', '$moyenne', '$filiere')");
    } else {
        mysqli_query($conn, "UPDATE pv SET moyenne_generale = '$moyenne' 
                             WHERE id_etu = '$idetu' AND filier = '$filiere'");
    }

    $etudiants_data[] = ['id' => $idetu, 'nom' => $nom, 'moyenne' => $moyenne];
    $myn += $moyenne;
}

$total_etudiants = count($etudiants_data);
$moyenne_globale = $total_etudiants > 0 ? floor(($myn / $total_etudiants) * 100) / 100 : 0;

$stats_query = mysqli_query($conn, "
    SELECT MIN(moyenne) AS min, MAX(moyenne) AS max, AVG(moyenne) AS avg
    FROM (
        SELECT SUM(coefficient * note) / SUM(coefficient) AS moyenne
        FROM notes
        WHERE num_Etudiant IN (SELECT id FROM personne WHERE filiere_id = $filiere)
        GROUP BY num_Etudiant
    ) AS moyennes
");
$stats_data = mysqli_fetch_assoc($stats_query);

$moyenne_max = floor(($stats_data['max'] ?? 0) * 100) / 100;
$moyenne_min = floor(($stats_data['min'] ?? 0) * 100) / 100;

$html_content = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: white; background-color: #f9f9f9; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; color: #333; }
        h1, h2 { color: #004085; }
        p { font-size: 14px; margin: 5px 0; }
        .summary { margin: 20px 0; font-size: 16px; }
        .summary strong { color: #004085; }
    </style>
</head>
<body>
    <h1>PV Global - Filière: $nom_filiere</h1>
    <p><strong>Généré par :</strong> Administration</p>
    <table>
        <thead>
            <tr><th>Nom / Prenom Étudiant</th><th>Moyenne</th></tr>
        </thead>
        ";

foreach ($etudiants_data as $etudiant) {
    $html_content .= "<tr><td>{$etudiant['nom']}</td><td>{$etudiant['moyenne']}</td></tr>";
}

$html_content .= "
    </table>
            <strong>Moyenne Globale: $moyenne_globale</strong><br>
            <strong>Moyenne Max : $moyenne_max</strong><br>
            <strong>Moyenne Min : $moyenne_min</strong><br>
        
</body>
</html>";

// Envoi des emails
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mostefaoui748@gmail.com';
    $mail->Password = 'wqge wkmq nfzx falp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('mostefaoui748@gmail.com', 'Administration');
    $mail->Subject = "PV Global - Filiere $nom_filiere";
    $mail->isHTML(true);

    foreach ($etudiants as $etudiant) {
        $mail->addAddress($etudiant['email'], $etudiant['Nom_pre']);
        $mail->Body = $html_content;
        $mail->send();
        $mail->clearAddresses();
    }
    echo "Emails envoyés avec succès.";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi des emails : {$mail->ErrorInfo}";
}