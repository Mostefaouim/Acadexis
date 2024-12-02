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
            $query_plus_10 = mysqli_query($conn, "SELECT COUNT(*) as count FROM pv WHERE moyenne_generale >= 10 AND filier = $filiere");
            $result_plus_10 = mysqli_fetch_assoc($query_plus_10);
            $count_plus_10 = $result_plus_10['count'] ?? 0;
            $query_minus_10 = mysqli_query($conn, "SELECT COUNT(*) as count FROM pv WHERE moyenne_generale < 10 AND filier = $filiere");
            $result_minus_10 = mysqli_fetch_assoc($query_minus_10);
            $count_minus_10 = $result_minus_10['count'] ?? 0;
            $total_students = $count_plus_10 + $count_minus_10;
            ?>
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Catégorie');
            data.addColumn('number', 'Pourcentage');
            data.addRows([
                ['Moyenne >= 10', <?= $count_plus_10 ?>],
                ['Moyenne < 10', <?= $count_minus_10 ?>]
            ]);
            var options = {
                title: "Répartition des étudiants par moyenne",
                fontName: "Poppins",
                slices: {
                    0: {
                        color: 'blue'
                    },
                    1: {
                        color: 'red'
                    }
                },

            };
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div id="chart_div" style="width: 600px; height: 400px;"></div>
</body>

</html>