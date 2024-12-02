<?php
require '../config.php';
?>
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: connect.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique</title>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            <?php
            $sexe_query = mysqli_query($conn, "SELECT role, COUNT(*) AS count FROM user GROUP BY role");
            ?>
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'role');
            data1.addColumn('number', 'Nombre user');
            data1.addRows([
                <?php
                $colorAssignments = [
                    'admin' => 'green',
                    'user' => 'blue'
                ];

                while ($query = mysqli_fetch_assoc($sexe_query)) {
                    $civilite = $query['role'];
                    $count = $query['count'];
                    echo "['$civilite', $count],";
                }
                ?>
            ]);

            var options = {
                title: "Le nombre d'user par Role Camembert.",
                fontName: "Poppins",
                slices: {
                    <?php
                    $sexe_query = mysqli_query($conn, "SELECT role, COUNT(*) AS count FROM user GROUP BY role");
                    $index = 0;
                    while ($query = mysqli_fetch_assoc($sexe_query)) {
                        $civilite = $query['role'];
                        $color = isset($colorAssignments[$civilite]) ? $colorAssignments[$civilite] : 'gray';
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
    <center>
        <div id="chart_div1" style="width: 500px; height: 500px;"></div>
        <div id="chart_div2" style="width: 500px; height: 500px;"></div>
    </center>

</body>

</html>