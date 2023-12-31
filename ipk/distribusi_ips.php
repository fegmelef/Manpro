<?php
    include("../api/connect.php");

    if (isset($_GET["angkatan1"]) && isset($_GET["angkatan2"])) {
        $angkatan1 = min($_GET['angkatan1'], $_GET['angkatan2']);
        $angkatan2 = max($_GET['angkatan1'], $_GET['angkatan2']);
    }

    if (isset($_GET["tahun"]) && isset($_GET["tahun2"])) {
        $tahun = min($_GET['tahun'], $_GET['tahun2']);
        $tahun2 = max($_GET['tahun'], $_GET['tahun2']);
    }

    if (isset($_GET["periode"])) {
        $periode = $_GET['periode'];
    }
    if (isset($_GET["val"])) {
        $val = $_GET['val'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data IPK</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <link rel="stylesheet" type="text/css" href="../css.css">

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
    <style>
        body {
            overflow-x: hidden;
        }

        th {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <?php include "../navbar/navbar_after_login.php"; ?>

    <!-- bread crumbs -->
    <div class="row">
        <div class="col-md-9 col-xs-9">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_ipk.php">Home</a></li>
                <li class="breadcrumb-item active">Distribusi IPS dan IPK</li>
            </ul>
        </div>
    </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedValue = $_POST['filtering'];

        if ($selectedValue == 'Pengaruh MK Terhadap IPS') {
            // Pemanggilan header() ada di sini
            header("location: ../ipk/pengaruhMK.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Rata-rata IPS dan IPK') {
            // Pemanggilan header() ada di sini
            header("location: ../ipk/rata2_ipk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'List Data') {
            // Pemanggilan header() ada di sini
            header("location: ../ipk/data_ipk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        }
    }
    ?>

    <!-- isi -->
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <p class="semester">Semester <span>
                        <?php echo $periode; ?>
                    </span> || Angkatan <span>
                        <?php echo $angkatan1; ?>
                    </span>-<span>
                        <?php echo $angkatan2; ?>
                    </span> || Tahun <span>
                        <?php echo $tahun; ?>
                    </span>-<span>
                        <?php echo $tahun2; ?>
                    </span></p>
            </div>

            <div class="col-md-4 col-xs-4">
                <form action="" method="post">
                    <div class="col-md-10 col-xs-10">
                        <select name="filtering" id="filtering" class="form-control" onchange="redirectPage()">
                            <option value="selected value"><?php echo $val; ?></option>
                            <option value="List Data">List Data</option>
                            <option value="Pengaruh MK Terhadap IPS">Pengaruh MK Terhadap IPS</option>
                            <!-- <option value="Penuruan IPS">Jumlah</option> -->
                            <option value="Rata-rata IPS dan IPK">Rata-rata IPS dan IPK</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 col-xs-2">
                        <input type="submit" value="Kirim" class="btn btn-primary">
                    </div>
                </form>
            </div>

            <div class="col-md-1 col-xs-1">
                    <div class="col-md-1 col-xs-1">
                        <svg id="downloadCSV" onclick="downloadAllTables()" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="cursor: pointer;">
                            <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                        </svg>
                    </div>
            </div>
        </div>

        <h3 style="margin-top: 20px">Distribusi IPS</h3>
        
        <!-- Render the pie chart -->
        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-12 col-xs-12 text-center">
                <div class="col-md-6 col-md-offset-3 col-xs-12">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- RATA-RATA IPS-->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <table class="table" id="Tabel_ips">
                    <tr>
                        <th class="bordered-header" scope="col" onclick="sortTable('Tabel_ips',0)">Nilai</th>
                        <th class="bordered-header" scope="col" onclick="sortTable('Tabel_ips',1)">Jumlah Mahasiswa</th>
                    </tr>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                hasil,
                                COUNT(nrp_hash) AS jumlah_mahasiswa
                            FROM (
                                SELECT 
                                    nrp_hash, 
                                    tahun, 
                                    semester,
                                    angkatan,
                                    sks,
                                    ips,
                                    CASE 
                                        WHEN ips = 4.0 THEN 'A'
                                        WHEN ips BETWEEN 3.5 AND 3.99 THEN 'B+'
                                        WHEN ips BETWEEN 3.0 AND 3.49 THEN 'B'
                                        WHEN ips BETWEEN 2.5 AND 2.99 THEN 'C+'
                                        WHEN ips BETWEEN 2.0 AND 2.49 THEN 'C'
                                        WHEN ips BETWEEN 1.0 AND 1.99 THEN 'D'
                                        WHEN ips BETWEEN 0 AND 0.99 THEN 'E'
                                    END AS hasil
                                FROM ips
                                WHERE 1=1";


                        if ($periode !== "All") {
                            $sql .= " AND semester = :periode";
                        }

                        if ($angkatan1 !== "All") {
                            $sql .= " AND angkatan between :angkatan1 and :angkatan2";
                        }

                        if ($tahun !== "All") {
                            $sql .= " AND tahun >= :tahun and tahun <= :tahun2";
                        }

                        $sql .= ") AS subquery
                                GROUP BY hasil";

                        $query = $conn->prepare($sql);

                        if ($periode !== "All") {
                            $query->bindParam(':periode', $periode, PDO::PARAM_STR);
                        }

                        if ($angkatan1 !== "All") {
                            $query->bindParam(':angkatan1', $angkatan1, PDO::PARAM_STR);
                        }
                        if ($angkatan2 !== "All") {
                            $query->bindParam(':angkatan2', $angkatan2, PDO::PARAM_STR);
                        }

                        if ($tahun !== "All") {
                            $query->bindParam(':tahun', $tahun, PDO::PARAM_STR);
                        }
                        if ($tahun2 !== "All") {
                            $query->bindParam(':tahun2', $tahun2, PDO::PARAM_STR);
                        }

                        $query->execute();
                        $result = $query->fetchAll();

                        if ($result) {
                            foreach ($result as $row) {
                                echo '<tr>
                                    <td class="bordered-cell">' . $row['hasil'] . '</td>
                                    <td class="bordered-cell">' . $row['jumlah_mahasiswa'] . '</td>
                                </tr>';
                            }
                        } else {
                            echo "Tidak ada data yang ditemukan.";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h3 style="margin-top: 20px">Distribusi IPK</h3>
        
        <!-- Render the pie chart -->
        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-12 col-xs-12 text-center">
                <div class="col-md-6 col-md-offset-3 col-xs-12">
                    <canvas id="pieChart2"></canvas>
                </div>
            </div>
        </div>

        <!-- RATA-RATA IPK-->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <table class="table" id="Tabel_ipk">
                    <tr>
                        <th class="bordered-header" scope="col" onclick="sortTable('Tabel_ipk',0)">Nilai</th>
                        <th class="bordered-header" scope="col" onclick="sortTable('Tabel_ipk',1)">Jumlah Mahasiswa</th>
                    </tr>
                    <tbody>
                        <?php
                        $sql2 = "SELECT 
                                hasil2,
                                COUNT(nrp_hash) AS jumlah_mahasiswa2
                            FROM (
                                SELECT 
                                    nrp_hash, 
                                    tahun, 
                                    semester,
                                    angkatan,
                                    ipk,
                                    CASE 
                                        WHEN ipk = 4.0 THEN 'A'
                                        WHEN ipk BETWEEN 3.5 AND 3.9999 THEN 'B+'
                                        WHEN ipk BETWEEN 3.0 AND 3.4999 THEN 'B'
                                        WHEN ipk BETWEEN 2.5 AND 2.9999 THEN 'C+'
                                        WHEN ipk BETWEEN 2.0 AND 2.4999 THEN 'C'
                                        WHEN ipk BETWEEN 1.0 AND 1.9999 THEN 'D'
                                        WHEN ipk BETWEEN 0 AND 0.9999 THEN 'E'
                                    END AS hasil2
                                FROM ipk
                                WHERE 1=1";

                        if ($periode !== "All") {
                            $sql2 .= " AND semester = :periode";
                        }

                        if ($angkatan1 !== "All") {
                            $sql2 .= " AND angkatan between :angkatan1 and :angkatan2";
                        }

                        if ($tahun !== "All") {
                            $sql2 .= " AND tahun >= :tahun and tahun <= :tahun2";
                        }

                        $sql2 .= ") AS subquery
                                GROUP BY hasil2";

                        $query2 = $conn->prepare($sql2);

                        if ($periode !== "All") {
                            $query2->bindParam(':periode', $periode, PDO::PARAM_STR);
                        }

                        if ($angkatan1 !== "All") {
                            $query2->bindParam(':angkatan1', $angkatan1, PDO::PARAM_STR);
                        }
                        if ($angkatan2 !== "All") {
                            $query2->bindParam(':angkatan2', $angkatan2, PDO::PARAM_STR);
                        }

                        if ($tahun !== "All") {
                            $query2->bindParam(':tahun', $tahun, PDO::PARAM_STR);
                        }
                        if ($tahun2 !== "All") {
                            $query2->bindParam(':tahun2', $tahun2, PDO::PARAM_STR);
                        }

                        $query2->execute();
                        $result2 = $query2->fetchAll();

                        if ($result2) {
                            foreach ($result2 as $row2) {
                                echo '<tr>
                                    <td class="bordered-cell">' . $row2['hasil2'] . '</td>
                                    <td class="bordered-cell">' . $row2['jumlah_mahasiswa2'] . '</td>
                                </tr>';
                            }
                        } else {
                            echo "Tidak ada data yang ditemukan.";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <!-- Include PHP data directly in the JavaScript section -->
    <script>
        // Your PHP data as JavaScript array
        var data = <?php echo json_encode($result); ?>;
        var data2 = <?php echo json_encode($result2); ?>;

        // Extract labels and values from the data array
        var labels = data.map(item => item.hasil); // Update 'hasil' with the actual field name
        var values = data.map(item => item.jumlah_mahasiswa);

        var labels2 = data2.map(item => item.hasil2); // Update 'hasil' with the actual field name
        var values2 = data2.map(item => item.jumlah_mahasiswa2);

        // Define specific colors for the dataset
        var colors = ['#FFC3A0', '#FFDCB0', '#FFD2D2', '#B0E57C', '#9EDAE2', '#C0C0C0', '#FFD700'];
        console.log(data); // Check the data in the browser's console
        console.log(data2); // Check the data in the browser's console

        // Create the chart using the data and specific colors
        var ctx = document.getElementById('pieChart').getContext('2d');
        var ctx2 = document.getElementById('pieChart2').getContext('2d');

        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff',
                        formatter: (value, ctx) => {
                            let dataset = ctx.chart.data.datasets[0];
                            let total = dataset.data.reduce((acc, data) => acc + data, 0);
                            let percentage = ((value / total) * 100).toFixed(2) + "%";
                            return percentage;
                        },
                        anchor: 'end',
                        align: 'start',
                    },
                },
            },
        });

        var myPieChart2 = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: labels2,
                datasets: [{
                    data: values2,
                    backgroundColor: colors,
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff',
                        formatter: (value2, ctx2) => {
                            let dataset = ctx2.chart.data.datasets[0];
                            let total = dataset.data.reduce((acc, data) => acc + data, 0);
                            let percentage = ((value2 / total) * 100).toFixed(2) + "%";
                            return percentage;
                        },
                        anchor: 'end',
                        align: 'start',
                    },
                },
            },
        });
    </script>


    </div>

    <script>
        var sort = "ascending";
        function sortTable(id,n) {

            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById(id);
            switching = true;
            rows = table.getElementsByTagName("TR");
            // console.log(sort);
            for (i = 1; i < (rows.length - 1); i++) {
                if (n == 0) {
                    max = rows[1].getElementsByTagName("TD")[1].textContent.toString();
                    min = "";
                } else {
                    max = 0;
                    min = Infinity;
                }

                for (j = i; j < (rows.length); j++) {
                    shouldSwap = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[j].getElementsByTagName("TD")[n];

                    if (n == 0 || n == 2) {
                        xValue = parseInt(x.textContent.toString());
                        yValue = parseInt(y.textContent.toString());
                    } else {
                        xValue = x.textContent.toLowerCase();
                        yValue = y.textContent.toLowerCase();
                    }

                    if (sort == "ascending") {
                        if (max < yValue) {
                            max = yValue;
                            index = j;
                        }
                    } else if (sort == "descending") {
                        if (min > yValue) {
                            min = yValue;
                            index = j;
                        }
                    }

                }
                if (sort == "ascending") {
                    // console.log(max);  
                    if (xValue <= max) {
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                } else {
                    // console.log(min);
                    if (xValue >= min) {
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                }
            }
            if (sort == "ascending") {
                sort = "descending";
            } else {
                sort = "ascending";
            }
            // console.log(rows)
        }

        function downloadAllTables() {
                    downloadCSV('Rata-Rata IPS.csv', 'Tabel_ips'); // Download first table
                    downloadCSV('Rata-Rata IPK.csv', 'Tabel_ipk'); // Download second table
                    // ... add downloadCSV for other tables here
                }

                function downloadCSV(filename, tableId) {
                    var table = document.getElementById(tableId); // Get the table element
                    var rows = Array.from(table.querySelectorAll('tr')); // Get all rows in the table

                    // Create a CSV content string
                    var csvContent = rows.map(function (row) {
                        var rowData = Array.from(row.querySelectorAll('th, td'))
                            .map(function (cell) {
                                return cell.textContent;
                            })
                            .join(',');
                        return rowData;
                    }).join('\n');

                    // Create a Blob object with the CSV content
                    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    if (navigator.msSaveBlob) {
                        // For IE and Edge browsers
                        navigator.msSaveBlob(blob, filename);
                    } else {
                        // For other browsers
                        var link = document.createElement('a');
                        if (link.download !== undefined) {
                            var url = URL.createObjectURL(blob);
                            link.setAttribute('href', url);
                            link.setAttribute('download', filename);
                            link.style.visibility = 'hidden';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    }
                }
    </script>
</body>

</html>