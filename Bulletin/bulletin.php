<?php

require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

$idetu = $_POST['num'];
if (isset($_POST['bulletin'])) {
    $sql = "SELECT * FROM personne WHERE id = $idetu";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $etudiant = mysqli_fetch_assoc($result);
        $email = $etudiant['email'];
        $nom = $etudiant['Nom_pre'];
?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <title>Gestion des Notes</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    padding: 20px;
                    border: 1px solid #ccc;
                }

                label {
                    font-weight: bold;
                }

                #td {
                    background-color: black;
                }

                table {
                    width: 50%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-family: Arial, sans-serif;
                }

                th {
                    background-color: #f9f9f9;
                }

                td {
                    background-color: #f9f9f9;
                }
            </style>
        </head>

        <body>
            <div>
                <center>
                    <h2>Bulletin de Notes</h2>
                </center>
            </div>
            <center>
            </center><br>
            <form method="POST" enctype="multipart/form-data" id="form" action="mailer.php">
                <input type="hidden" name="num" value="<?= $idetu; ?>">
                <input type="hidden" name="rechercher" value="1">
                <br>
                <div>
                    <label for="civility">Civilité :</label>
                    <input type="text" name="civility" id="civility" value="<?= $etudiant['Civilité'] ?? ""; ?>" readonly>
                </div>

                <div style="float: right;">
                    <?php
                    if (isset($etudiant) && !empty($etudiant['image'])) {
                        echo "<img src='../image/" . htmlspecialchars($etudiant['image']) . "' alt='Image' id='imagePreview' style='max-width: 250px;'>";
                    } else {
                        echo "<p>Aucune image disponible</p>";
                    }
                    ?>
                </div>
                <br>
                <div>
                    <label for="nom">Nom/Prénom :</label>
                    <input type="text" id="nom" name="nom" value="<?= $etudiant['Nom_pre'] ?? ''; ?>" readonly>
                </div><br>
                <div>
                    <label for="email">Email : <strong><?= $etudiant['email'] ?? ''; ?> </strong></label>

                    <input type="hidden" id="email" name="email" value="<?= $etudiant['email'] ?? ''; ?>" readonly>
                </div><br>
                <div>
                    <label for="filiere">Filière :</label>
                    <?php
                    $filieres_result = mysqli_query($conn, "SELECT * FROM filieres");
                    $filiere_name = '---';
                    while ($row = mysqli_fetch_assoc($filieres_result)) {
                        if ($etudiant['filiere_id'] == $row['id']) {
                            $filiere_name = $row['nom_filiere'];
                            break;
                        }
                    }
                    ?>
                    <input type="text" name="filiere" id="filiere" value="<?= $filiere_name; ?>" readonly>

                </div><br>
                <div>
                    <table border="2" style="text-align: center; margin-bottom: 20px;">
                        <tr>
                            <th>Code Module</th>
                            <th>Module</th>
                            <th>Coefficient</th>
                            <th>Note</th>
                        </tr>
                        <?php
                        $module_query = mysqli_query($conn, "SELECT * FROM notes WHERE num_Etudiant = $idetu ");
                        while ($mod_data = mysqli_fetch_assoc($module_query)) {
                            $module_id = $mod_data['nom_module'] ?? null;
                            $note = $mod_data['note'] ?? '--';
                            $coef = $mod_data['coefficient'] ?? '--';
                            $code = $mod_data['code_module'] ?? '--';

                            if ($module_id) {
                                $module_name_query = mysqli_query($conn, "SELECT nom FROM modules WHERE id = $module_id");
                                $module_result = mysqli_fetch_assoc($module_name_query);
                                $module_name = $module_result['nom'] ?? '--';
                            }
                        ?>
                            <tr>
                                <td><?= $code ?></td>
                                <td><?= $module_name ?></td>
                                <td><?= $coef ?></td>
                                <td><?= $note ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td id="td" colspan="6"></td>
                        </tr>

                        <?php
                        $somme = mysqli_query($conn, "SELECT SUM(coefficient) AS coef, SUM(coefficient*note) AS somme, SUM(coefficient*note)/SUM(coefficient) AS moyenne FROM notes WHERE num_Etudiant = $idetu");
                        $somme = mysqli_fetch_assoc($somme);
                        ?>
                        <tr>
                            <td colspan="2"><strong>Total Coefficient</strong></td>
                            <td colspan="2"><strong><?= $somme['coef']; ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Somme Coefficient*Notes</strong></td>
                            <td colspan="2"><strong><?= $somme['somme']; ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Moyenne</strong></td>
                            <td colspan="2"><strong><?= floor($somme['moyenne'] * 100) / 100; ?></strong></td>
                        </tr>
                    </table>
                </div>
                <br>
                <input type="submit" value="Send Mail" name="mail" formaction="mail.php">
                <input type="submit" value="Send PHPMAILER" name="mailer"><br>
            </form>
            <br><button type="submit" id="print" onclick="printForm()">Imprimer</button>
        </body>

        </html>
<?php
    } else {
        echo "<font color='red'>Étudiant Inexistant</font>";
    }
}
?>
<script>
    function printForm() {
        let printButton = document.getElementById("print");
        printButton.style.display = "none";
        window.print();
    }
</script>