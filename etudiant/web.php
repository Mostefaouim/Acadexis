<?php
require '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}

$civility = $_POST['civility'] ?? '';
$nom = $_POST['nom'] ?? '';
$email = $_POST['email'] ?? '';
$adresse = $_POST['adresse'] ?? '';
$NoPostal = $_POST['NoPostal'] ?? '';
$localite = $_POST['localite'] ?? '';
$Pays = $_POST['Pays'] ?? '';
$platforms = isset($_POST['platforms']) ? implode(", ", $_POST['platforms']) : '';
$Applications = isset($_POST['Applications']) ? implode(", ", $_POST['Applications']) : '';
$sports = $_POST['sports'] ?? [];
$filiere = $_POST['filiere'] ?? [];
$Pays = $_POST['Pays'] ?? [];
if (isset($_POST['Enregistrer'])) {
    $errors = [];
    if (empty($civility)) $errors[] = 'Le champ "Civilité" est vide.';
    if (empty($nom)) $errors[] = 'Le champ "Nom" est vide.';
    if (empty($email)) $errors[] = 'Le champ "Email" est vide.';
    if (empty($adresse)) $errors[] = 'Le champ "Adresse" est vide.';
    if (empty($NoPostal)) $errors[] = 'Le champ "Code Postal" est vide.';
    if (empty($localite)) $errors[] = 'Le champ "Localité" est vide.';
    if (empty($Pays)) $errors[] = 'Le champ "Pays" est vide.';
    if (empty($platforms)) $errors[] = 'Veuillez sélectionner au moins une plateforme.';
    if (empty($Applications)) $errors[] = 'Veuillez sélectionner au moins une application.';
    if (empty($sports)) $errors[] = 'Veuillez sélectionner au moins un sport.';
    if (empty($filiere)) $errors[] = 'Veuillez sélectionner une filiere.';
    $filename = "";
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $image = $_FILES['image']['name'];
        $filename = uniqid() . '_' . $image;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], '../image/' . $filename)) {
            die('<font color="red">Erreur lors du téléchargement de l\'image.</font>');
        }
    }
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<font color="red">' . htmlspecialchars($error) . '</font><br>';
        }
    } else {
        $sql = "INSERT INTO personne (Civilité, Nom_pre, email, Adresse, code_post, localite, plat_form, applications, image,filiere_id,nationalite_id) 
                VALUES ('$civility', '$nom','$email' ,'$adresse', '$NoPostal', '$localite', '$platforms', '$Applications', '$filename', $filiere, $Pays)";

        if (mysqli_query($conn, $sql)) {
            $personne_id = mysqli_insert_id($conn);


            foreach ($sports as $sport_id) {
                $sql_sport = "INSERT INTO personne_sport (personne_id, sport_id) VALUES ('$personne_id', '$sport_id')";
                mysqli_query($conn, $sql_sport);
            }

            echo "<font color='green'>Vos informations ont été ajoutées avec succès.</font>";
        } else {
            echo "<font color='red'>Erreur SQL: " . mysqli_error($conn) . "</font>";
        }
    }
} elseif (isset($_POST['AffichageListe'])) {
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT p.*, GROUP_CONCAT(s.sport_name SEPARATOR ', ') AS sports
            FROM personne p
            LEFT JOIN personne_sport ps ON p.id = ps.personne_id
            LEFT JOIN sport s ON ps.sport_id = s.id
            GROUP BY p.id";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<center><h1>Tous Les Étudiants</h1></center>";
?>
        <center>
            <table border="1" style="text-align: center;">
                <tr>
                    <th>#Id</th>
                    <th>Civilité</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Code Postal</th>
                    <th>Localité</th>
                    <th>Pays</th>
                    <th>Plateformes</th>
                    <th>Applications</th>
                    <th>Sports</th>
                    <th>Filiere</th>
                    <!-- <th>Image</th> -->
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['Civilité']; ?></td>
                        <td><?= $row['Nom_pre']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['Adresse']; ?></td>
                        <td><?= $row['code_post']; ?></td>
                        <td><?= $row['localite']; ?></td>
                        <?php $result2 = mysqli_query($conn, "SELECT * FROM nationalité");
                        $query = mysqli_fetch_assoc($result2) ?>
                        <td>
                            <?php
                            $result2 = mysqli_query($conn, "SELECT * FROM nationalité");
                            $nationalite = [];
                            while ($data = mysqli_fetch_assoc($result2)) {
                                $nationalite[$data['id']] = $data['nationalite'];
                            }
                            if (array_key_exists($row['nationalite_id'], $nationalite)) {
                                echo $nationalite[$row['nationalite_id']];
                            } else {
                                echo '-';
                            }
                            ?>
                        <td><?= $row['plat_form']; ?></td>
                        <td><?= $row['applications']; ?></td>
                        <td><?= $row['sports']; ?></td>
                        <?php $result1 = mysqli_query($conn, "SELECT * FROM filieres");
                        $data = mysqli_fetch_assoc($result1) ?>
                        <td>
                            <?php
                            $result1 = mysqli_query($conn, "SELECT * FROM filieres");
                            $filieres = [];
                            while ($data = mysqli_fetch_assoc($result1)) {
                                $filieres[$data['id']] = $data['nom_filiere'];
                            }
                            if (array_key_exists($row['filiere_id'], $filieres)) {
                                echo $filieres[$row['filiere_id']];
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>

                        <!-- <td>
                            <?php //  if (!empty($row['image'])): 
                            ?>
                             <img src="<?php //echo '../image/' . htmlspecialchars($row['image']); 
                                        ?>" alt="Image" style="max-width: 50px;"> -->
                        <?php  // else: 
                        ?>
                        <!-- <span>Aucune image disponible</span>
                            <?php  // endif; 
                            ?>
                        </td> -->
                    </tr>
                <?php } ?>
            </table>
            <br><br><a href="index.php" style="text-decoration: none; color:black;">Menu Principale</a>
        </center>
<?php
    } else {
        echo "0 résultats.";
    }
}
?>