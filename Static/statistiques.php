<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/connect.php");
    exit();
}
$filiere = $_POST['filiere'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique</title>
    <link rel="stylesheet" href="../styles/style.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            <?php
            $sexe_query = mysqli_query($conn, "SELECT Civilité, COUNT(*) AS count FROM personne GROUP BY Civilité");
            ?>
            var data1 = new google.visualization.DataTable();;
            data1.addColumn('string', 'Sexe');;
            data1.addColumn('number', 'Nombre Etudiant');;
            data1.addRows([
                <?php
                $colorAssignments = [
                    'Monsieur' => 'blue',
                    'Madame' => 'orange',
                    'Mademoiselle' => 'red'
                ];

                while ($query = mysqli_fetch_assoc($sexe_query)) {
                    $civilite = $query['Civilité'];
                    $count = $query['count'];

                    $color = isset($colorAssignments[$civilite]);

                    echo "['$civilite', $count],";
                }
                ?>
            ]);
            var options = {
                title: "Le nombre d'étudiants par sexe Camembert.",
                fontName: "Poppins",
                slices: {
                    <?php
                    $sexe_query = mysqli_query($conn, "SELECT Civilité, COUNT(*) AS count FROM personne GROUP BY Civilité");
                    $index = 0;
                    while ($query = mysqli_fetch_assoc($sexe_query)) {
                        $civilite = $query['Civilité'];
                        $color = $colorAssignments[$civilite];
                        echo "$index: { color: '$color' },";
                        $index++;
                    }
                    ?>
                }
            };
            var chart1 = new google.visualization.PieChart(document.getElementById('chart_div1'));
            chart1.draw(data1, options);
            var options2 = {
                title: "Le nombre d'étudiants par sexe Histogramme",
                // fontName: "Arial",
                hAxis: {
                    title: 'Sexe'
                },
                vAxis: {
                    title: 'Nombre Etudiant'
                },
                legend: {
                    position: 'none'
                }
            };
            var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
            chart2.draw(data1, options2);
        }
    </script>
</head>

<body>
    <div id="chart_div1" style="width: 500px; height: 500px;"></div>
    <div id="chart_div2" style="width: 500px; height: 500px;"></div>
</body>

</html>