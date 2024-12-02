<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

if (isset($_POST['mail'])) {
    $idetu = $_POST['num'];

    $sql = "SELECT * FROM personne WHERE id = $idetu";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $etudiant = mysqli_fetch_assoc($result);
        $email = $etudiant['email'];
        $nom = htmlspecialchars($etudiant['Nom_pre']);
        $civilite = htmlspecialchars($etudiant['Civilité']);
        $photo = htmlspecialchars($etudiant['image']);
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
        <body>";

        $html_content .= "<h2>Bulletin de Notes pour $nom</h2>";
        $html_content .= "<p><strong>Civilité :</strong> $civilite</p>";
        $html_content .= "<p><strong>Nom/Prénom :</strong> $nom</p>";
        $html_content .= "<p><strong>Email :</strong> $email</p>";

        $filieres_result = mysqli_query($conn, "SELECT * FROM filieres");
        $filiere_name = '---';
        while ($row = mysqli_fetch_assoc($filieres_result)) {
            if ($etudiant['filiere_id'] == $row['id']) {
                $filiere_name = $row['nom_filiere'];
                break;
            }
        }
        $html_content .= '<p><strong>Filière :</strong> ' . htmlspecialchars($filiere_name ?? '') . '</p><br><br><br>';

        $html_content .= "<table>";
        $html_content .= "<tr><th>Code Module</th><th>Module</th><th>Coefficient</th><th>Note</th></tr>";

        $module_query = mysqli_query($conn, "SELECT * FROM notes WHERE num_Etudiant = $idetu");
        while ($mod_data = mysqli_fetch_assoc($module_query)) {
            $module_id = $mod_data['nom_module'];
            $note = $mod_data['note'];
            $coef = $mod_data['coefficient'];
            $code = $mod_data['code_module'];

            $module_name_query = mysqli_query($conn, "SELECT nom FROM modules WHERE id = $module_id");
            $module_result = mysqli_fetch_assoc($module_name_query);
            $module_name = htmlspecialchars($module_result['nom']);

            $html_content .= "<tr><td>$code</td><td>$module_name</td><td>$coef</td><td>$note</td></tr>";
        }
        $somme_query = mysqli_query($conn, "
            SELECT 
                SUM(coefficient) AS coef,
                SUM(coefficient * note) AS somme,
                SUM(coefficient * note) / SUM(coefficient) AS moyenne 
            FROM notes WHERE num_Etudiant = $idetu");
        $somme = mysqli_fetch_assoc($somme_query);

        $html_content .= "<tr><td colspan='3'><strong>Total Coefficient</strong></td><td><strong>{$somme['coef']}</strong></td></tr>";
        $html_content .= "<tr><td colspan='3'><strong>Somme Coefficient*Notes</strong></td><td><strong>{$somme['somme']}</strong></td></tr>";
        $moyenne = floor($somme['moyenne'] * 100) / 100;
        $html_content .= "<tr><td colspan='3'><strong>Moyenne</strong></td><td><strong>" . $moyenne . "</strong></td></tr>";
        $html_content .= "</table>";

        $html_content .= "</body></html>";

        $subject = "Bulletin de Notes - $nom (MAIL)";
        $headers = "From: Mostefaoui Mohammed <mostefaoui748@gmail.com>\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $file_name = "bulletin_{$idetu}_" . date("Ymd_His") . ".html";
        $file_path = "D:/xampp/mailoutput/$file_name";  

        file_put_contents($file_path, $html_content);

        $boundary = md5(time()); 
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $message = "--$boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n";
        $message .= "\r\n$html_content\r\n";
        $message .= "--$boundary\r\n";
        $message .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
        $message .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        //$message .= "\r\n" . chunk_split(base64_encode(file_get_contents($file_path))) . "\r\n";
        $message .= "--$boundary--\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "L'e-mail a été envoyé avec succès à $email.";
        } else {
            echo "L'envoi de l'e-mail a échoué.";
        }
    } else {
        echo "Étudiant introuvable.";
    }
}
?>
