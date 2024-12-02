<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../config.php';

if (isset($_POST['mailer'])) {
    require '../vendor/autoload.php';

    $idetu = $_POST['num'];

    $sql = "SELECT * FROM personne WHERE id = $idetu";
    $result = mysqli_query($conn, $sql);
    $etudiant = mysqli_fetch_assoc($result);

    if (!$etudiant) {
        echo "Étudiant introuvable.";
        exit();
    }

    $email = htmlspecialchars($etudiant['email'], ENT_QUOTES, 'UTF-8');
    $nom = htmlspecialchars($etudiant['Nom_pre'], ENT_QUOTES, 'UTF-8');
    $civilite = htmlspecialchars($etudiant['Civilité'] ?? 'Non spécifié', ENT_QUOTES, 'UTF-8');

    $module_query = mysqli_query($conn, "
        SELECT notes.code_module, notes.nom_module, notes.coefficient, notes.note, modules.nom 
        FROM notes
        JOIN modules ON notes.nom_module = modules.id 
        WHERE notes.num_Etudiant = $idetu
    ");
    $modules_data = [];
    while ($mod_data = mysqli_fetch_assoc($module_query)) {
        $modules_data[] = [
            'code' => htmlspecialchars($mod_data['code_module'], ENT_QUOTES, 'UTF-8'),
            'module' => htmlspecialchars($mod_data['nom'], ENT_QUOTES, 'UTF-8'),
            'coef' => htmlspecialchars($mod_data['coefficient'], ENT_QUOTES, 'UTF-8'),
            'note' => htmlspecialchars($mod_data['note'], ENT_QUOTES, 'UTF-8')
        ];
    }

    $somme = mysqli_query($conn, "
        SELECT 
            SUM(coefficient) AS coef, 
            SUM(coefficient * note) AS somme, 
            SUM(coefficient * note) / SUM(coefficient) AS moyenne 
        FROM notes 
        WHERE num_Etudiant = $idetu
    ");
    $somme = mysqli_fetch_assoc($somme);

    $totalCoef = $somme['coef'] ?? 0;
    $sommeCoefNote = $somme['somme'] ?? 0;
    $moyenne = floor(($somme['moyenne'] ?? 0) * 100) / 100;

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mostefaoui748@gmail.com';
        $mail->Password = 'wqge wkmq nfzx falp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('mostefaoui748@gmail.com', 'Mohammed Mostefaoui');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Bulletin de Notes $nom (PhpMailer)";

        $html_content = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    background-color: #f9f9f9;
                    padding: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-size: 16px;
                    color:black
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                }
                th {
                    background-color: #f4f4f4;
                    color: #333;
                }
                h2 {
                    color: #004085;
                }
                p {
                    font-size: 14px;
                    margin: 5px 0;
                    color:black;
                }
            </style>
        </head>
        <body>
            <h2>Bulletin de Notes pour $nom</h2>
            <p><strong>Civilité :</strong> $civilite</p>
            <p><strong>Nom/Prénom :</strong> $nom</p>
            <p><strong>Email :</strong> $email</p>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Code Module</th>
                        <th>Module</th>
                        <th>Coefficient</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($modules_data as $module) {
            $html_content .= "
                    <tr>
                        <td>{$module['code']}</td>
                        <td>{$module['module']}</td>
                        <td>{$module['coef']}</td>
                        <td>{$module['note']}</td>
                    </tr>";
        }

        $html_content .= "
                    <tr>
                        <td colspan='3'><strong>Total Coefficient</strong></td>
                        <td><strong>$totalCoef</strong></td>
                    </tr>
                    <tr>
                        <td colspan='3'><strong>Somme Coefficient*Notes</strong></td>
                        <td><strong>$sommeCoefNote</strong></td>
                    </tr>
                    <tr>
                        <td colspan='3'><strong>Moyenne</strong></td>
                        <td><strong>$moyenne</strong></td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>";

        $mail->Body = $html_content;
        $mail->send();
        echo "L'e-mail a été envoyé avec succès à $email.";
    } catch (Exception $e) {
        echo "L'e-mail n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
    }
}
