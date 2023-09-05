<?php
include "config.php";
$query = 'select * from data';
$result = $mysqli->execute_query($query);


$relevance = "SELECT relevance,likelihood, end_year from data GROUP BY end_year";
$relevanceRecords = $mysqli->execute_query($relevance);


$intensity = "SELECT intensity, end_year FROM data GROUP BY end_year";
$intensityRecords = $mysqli->execute_query($intensity);

$year = "SELECT COUNT(end_year) as ey,end_year from data GROUP BY end_year";
$yearRecords = $mysqli->execute_query($year);
$sql = "SELECT country, COUNT(*) AS count
        FROM data
        GROUP BY country";

$countryresult = $mysqli->query($sql);
$data = array();
$data[] = ['Country', 'Count'];
$skipFirstRow = true;

if ($countryresult->num_rows > 0) {
    while ($row = $countryresult->fetch_assoc()) {
        if ($skipFirstRow) {
            $skipFirstRow = false;
            continue;
        }
        $data[] = [$row['country'], (int)$row['count']];
    }
}

$topic = "SELECT topic, COUNT(*) AS count
        FROM data
        GROUP BY topic";

$topicRecords = $mysqli->query($topic);
$topicdata = array();
$topicdata[] = ['Topic', 'Count'];
$skipFirstRowOfTopic = true;

if ($topicRecords->num_rows > 0) {
    while ($row = $topicRecords->fetch_assoc()) {
        if ($skipFirstRowOfTopic) {
            $skipFirstRowOfTopic = false;
            continue;
        }
        $topicdata[] = [$row['topic'], (int)$row['count']];
    }
}
?>

<!DOCTYPE html>
<!--=== Coding by CodingLab | www.codinglabweb.com === -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Admin Dashboard Panel</title>
</head>

<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="images/logo.png" alt="">
            </div>

            <span class="logo_name">Admin Dashboard</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Dahsboard</span>
                    </a></li>

            </ul>

            <ul class="logout-mode">
                <li><a href="#">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                        <span class="link-name">Dark Mode</span>
                    </a>

                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <img src="images/profile.jpg" alt="">
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">

                    <div id="curve_chart" style="width: 500px; height: 300px; border:1px solid black; margin: 3px 3px 3px 3px;"></div>

                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['corechart']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Year', 'Relevance', 'likelihood'],
                                <?php
                                while ($row = mysqli_fetch_assoc($relevanceRecords)) {
                                    echo "['" . $row["end_year"] . "', " . $row["relevance"] . "," . $row["likelihood"] . "],";
                                }
                                ?>
                            ]);

                            var options = {
                                title: 'Relevance, likelihood',
                                curveType: 'function',
                                legend: {
                                    position: 'bottom'
                                }
                            };

                            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                            chart.draw(data, options);
                        }
                    </script>
                    <div id="likelihood" style="width: 500px; height: 300px; border:1px solid black; margin: 3px 3px 3px 3px;"></div>

                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['corechart']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Year', 'Intensity'],
                                <?php
                                while ($row = mysqli_fetch_assoc($intensityRecords)) {
                                    echo "['" . $row["end_year"] . "', " . $row["intensity"] . "],";
                                }
                                ?>
                            ]);

                            var options = {
                                title: 'Intensity',
                                curveType: 'function',
                                legend: {
                                    position: 'bottom'
                                }
                            };

                            var chart = new google.visualization.LineChart(document.getElementById('likelihood'));

                            chart.draw(data, options);
                        }
                    </script>

                </div>
                <div class="boxes">
                    <div id="barchart_material" style="width: 500px; height: 300px; border:1px solid black; margin: 3px 3px 3px 3px;"></div>

                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Year', 'end_year'],
                                <?php
                                while ($row = mysqli_fetch_assoc($yearRecords)) {
                                    echo "['" . $row["end_year"] . "', " . $row["ey"] . "],";
                                }
                                ?>
                            ]);

                            var options = {
                                chart: {
                                    title: 'End Year Count',
                                    subtitle: '2018-2066',
                                },
                                bars: 'vertical' // Required for Material Bar Charts.
                            };

                            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>
                    <div id="regions_div" style="width: 500px; height: 300px; border:1px solid black; margin: 3px 3px 3px 3px;"></div>

                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['geochart'],
                        });
                        google.charts.setOnLoadCallback(drawRegionsMap);

                        function drawRegionsMap() {
                            var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

                            var options = {
                                title: 'Country',
                            };

                            var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

                            chart.draw(data, options);
                        }
                    </script>
                </div>
            </div>
            <div class="overview">
                <div class="boxes">
                    <div id="topic_barchart" style="width: 500px; height: 300px; border:1px solid black; margin: 3px 3px 3px 3px;"></div>

                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable(<?php echo json_encode($topicdata); ?>);

                            var options = {
                                chart: {
                                    title: 'Topics',
                                },
                                bars: 'vertical' // Required for Material Bar Charts.
                            };

                            var chart = new google.charts.Bar(document.getElementById('topic_barchart'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>
                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Total Data</span>
                </div>

                <div class="activity-data">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>End Year</th>
                                <th>Topics</th>
                                <th>Sector</th>
                                <th>Region</th>
                                <th>Pest</th>
                                <th>Source</th>
                                <th>SWOT</th>
                                <th>Country</th>
                                <th>City</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($result as $row) { ?>
                                <tr>
                                    <td><?php echo $row['end_year'];  ?></td>
                                    <td><?php echo $row['topic'];  ?></td>
                                    <td><?php echo $row['sector'];  ?></td>
                                    <td><?php echo $row['region'];  ?></td>
                                    <td><?php echo $row['pestle'];  ?></td>
                                    <td><?php echo $row['source'];  ?></td>
                                    <td><?php echo $row['swot'];  ?></td>
                                    <td><?php echo $row['country'];  ?></td>
                                    <td><?php echo $row['city'];  ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>End Year</th>
                                <th>Topics</th>
                                <th>Sector</th>
                                <th>Region</th>
                                <th>Pest</th>
                                <th>Source</th>
                                <th>SWOT</th>
                                <th>Country</th>
                                <th>City</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        new DataTable('#example');
    </script>
</body>

</html>