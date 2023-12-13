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
    <title>Data CPL</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css.css">

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
    <style>
        body {
            overflow-x: hidden;
        }

        th {
            cursor: pointer;
        }

        ;
    </style>
</head>

<body>
    <!-- navbar -->
    <?php include "../navbar/navbar_after_login.php"; ?>

    <!-- bread crumbs -->
    <div class="row">
        <div class="col-md-9 col-xs-9">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                <li class="breadcrumb-item active">Jumlah Mahasiswa Mengulang MK</li>
            </ul>
        </div>

        <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mengambil nilai dropdown yang dipilih
            $selectedValue = $_POST['filtering'];

            // Membuat pernyataan if berdasarkan nilai dropdown
            if ($selectedValue == 'Daftar Mahasiswa Dibawah Rata-rata Nilai') {
                header("location: ../cpl/reporting.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&tahun2=$tahun2&&periode=$periode&&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'List Data') {
                header("location: ../cpl/data_cpl.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&tahun2=$tahun2&&periode=$periode&&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'Rata-rata Nilai') {
                header("location: ../cpl/rata_rata.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&tahun2=$tahun2&&periode=$periode&&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'Distribusi Nilai') {
                header("location: ../cpl/distribusi.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&tahun2=$tahun2&&periode=$periode&&val=$selectedValue");
                exit;
            }


        }
        ?>

        <!-- isi -->
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-xs-7">
                    <p class="semester">Semester <span>
                            <?php echo $periode; ?>
                        </span> || Angkatan <span>
                            <?php echo $angkatan1; ?>
                        </span>-<span>
                            <?php echo $angkatan2; ?>
                        </span> || Tahun <span>
                            <?php echo $tahun; ?>
                        </span><span>
                            <?php echo $tahun2; ?>
                        </span></p>
                </div>

                <div class="col-md-4 col-xs-4">
                    <form action="" method="post">
                        <div class="col-md-10 col-xs-10">
                            <select name="filtering" id="filtering" class="form-control" onchange="redirectPage()">
                                <option value="selected value">
                                    <?php echo $val; ?>
                                </option>
                                <option value="List Data">List Data</option>
                                <option value="Distribusi Nilai">Distribusi Nilai</option>
                                <!-- <option value="Jumlah Mahasiswa Mengulang MK">Jumlah Mahasiswa Mengulang MK</option> -->
                                <option value="Rata-rata Nilai">Rata-rata Nilai</option>
                                <option value="Daftar Mahasiswa Dibawah Rata-rata Nilai">Daftar Mahasiswa Dibawah
                                    Rata-rata Nilai</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <input type="submit" value="Kirim" class="btn btn-primary">
                        </div>
                    </form>
                </div>

                <div class="col-md-1 col-xs-1">
                    <div class="col-md-1 col-xs-1">
                        <svg id="downloadCSV" onclick="downloadAllTables()" xmlns="http://www.w3.org/2000/svg"
                            height="1em" viewBox="0 0 512 512" style="cursor: pointer;">
                            <path
                                d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>

                    </div>
                </div>
            </div>

            <?php
            $query1 = "SELECT subquery.mk AS 'Mata Kuliah', COUNT(subquery.nrp_hash) AS 'Jumlah Mahasiswa Tidak Lulus'
                            FROM (
                                SELECT mk.mk, kelas_nilaicpmk.nrp_hash, mhsw.tahun AS 'angkatan', periode.tahun, periode.semester AS 'semester'
                                FROM kelas_cpmk
                                    JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                                    JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                                    JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                                    JOIN mk ON kelas.id_mk = mk.id_mk
                                    JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                                    JOIN periode ON kelas.id_periode = periode.id_periode  
                                    JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                                    GROUP BY mk.mk, kelas_nilaicpmk.nrp_hash
                                    HAVING SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) < 55.5";

            if ($angkatan1 !== 'All') {
                $query1 .= " AND angkatan BETWEEN $angkatan1 and $angkatan2";
            }
            if ($periode !== 'All') {
                $query1 .= " AND semester = $periode";
            }
            if ($tahun !== 'All') {
                $query1 .= " AND tahun >= '$tahun' and tahun <= '$tahun2'";
            }

            $query1 .= ") AS subquery GROUP BY subquery.mk";
            $query1 = $conn->prepare($query1);
            $query1->execute();


            $labels = [];
            $values = [];
            while ($row1 = $query1->fetch()) {
                $labels[] = $row1['Mata Kuliah'];
                $values[] = $row1['Jumlah Mahasiswa Tidak Lulus'];
            }
            ?>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <canvas id="myChart" width="400" height="200"></canvas>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Get data from PHP and convert it to a JavaScript array
                            var label = <?php echo json_encode($labels); ?>;
                            var value = <?php echo json_encode($values); ?>;

                            // Extract relevant data for the chart
                            // var labels = data.map(function (item) {
                            //     return item.mk;
                            // });

                            // var values = data.map(function (item) {
                            //     return item['nilai CPL'];
                            // });

                            // Create a bar chart
                            var ctx = document.getElementById('myChart').getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: label,
                                    datasets: [{
                                        label: 'Jumlah Mahasiswa Tidak Lulus',
                                        data: value,
                                        backgroundColor: 'rgba(255, 192, 203, 1)',
                                        borderColor: 'rgba(255, 192, 203, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                </div>
            </div>

            <h3>Jumlah yang Tidak Lulus </h3>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <table class="table" id="mata_kuliah">
                        <tr>
                            <th class="bordered-header" scope="col" onclick="sortTable(0, 'mata_kuliah')">No</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(1,'mata_kuliah')">Mata Kuliah
                            </th>
                            <th class="bordered-header" scope="col" onclick="sortTable(2, 'mata_kuliah')">Jumlah
                                Mahasiswa Tidak Lulus</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query1 = "SELECT subquery.mk AS 'Mata Kuliah', COUNT(subquery.nrp_hash) AS 'Jumlah Mahasiswa Tidak Lulus'
                            FROM (
                                SELECT mk.mk, kelas_nilaicpmk.nrp_hash, mhsw.tahun AS 'angkatan', periode.tahun, periode.semester AS 'semester'
                                FROM kelas_cpmk
                                    JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                                    JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                                    JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                                    JOIN mk ON kelas.id_mk = mk.id_mk
                                    JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                                    JOIN periode ON kelas.id_periode = periode.id_periode  
                                    JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                                    GROUP BY mk.mk, kelas_nilaicpmk.nrp_hash
                                    HAVING SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) < 55.5";

                            if ($angkatan1 !== 'All') {
                                $query1 .= " AND angkatan between $angkatan1 and $angkatan2";
                            }
                            if ($periode !== 'All') {
                                $query1 .= " AND semester = $periode";
                            }
                            if ($tahun !== 'All') {
                                $query1 .= " AND tahun >= '$tahun' and tahun <= '$tahun2'";
                            }

                            $query1 .= ") AS subquery GROUP BY subquery.mk";
                            $query1 = $conn->prepare($query1);
                            $query1->execute();

                            $rowNum = 1;
                            while ($row1 = $query1->fetch()) {
                                echo '<tr>
                                        <td class="bordered-cell"scope="row">' . $rowNum . '</td> 
                                        <td class="bordered-cell">' . $row1['Mata Kuliah'] . '</td>
                                        <td class="bordered-cell">' . $row1['Jumlah Mahasiswa Tidak Lulus'] . '</td>
                                    </tr>';

                                $rowNum++;
                            }

                            ?>

                        </tbody>
                    </table>
                </div>
            </div>


            <h3>Detail Mahasiswa Tidak Lulus</h3>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <table class="table" id="detail">
                        <tr>
                            <th class="bordered-header" scope="col" onclick="sortTable(0, 'detail')">No</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(1, 'detail')">Nilai CPL</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(2, 'detail')">ID IKCPL</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(3, 'detail')">ID CPL</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(4, 'detail')">Mata Kuliah</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(5, 'detail')">NRP</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(6, 'detail')">Tahun</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(7, 'detail')">Angkatan</th>

                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, mk.mk, mhsw.nrp_hash AS 'NRP', periode.tahun, mhsw.tahun AS 'angkatan', periode.semester
                            FROM kelas_cpmk
                            JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                            JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                            JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                            JOIN mk ON kelas.id_mk = mk.id_mk
                            JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                            JOIN periode ON kelas.id_periode = periode.id_periode  
                            JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                            GROUP BY mk.mk, kelas_nilaicpmk.nrp_hash
                            HAVING SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) < 55.5";

                            if ($angkatan1 !== 'All') {
                                $sql .= " AND angkatan between $angkatan1 and $angkatan2";
                            }
                            if ($periode !== 'All') {
                                $sql .= " AND semester = $periode";
                            }
                            if ($tahun !== 'All') {
                                $sql .= " AND tahun >= '$tahun' and tahun <= '$tahun2'";
                            }

                            $sql .= ' ORDER BY `mhsw`.`nrp_hash` ASC';

                            $query = $conn->prepare($sql);
                            $query->execute();

                            $rowNum = 1;
                            while ($row = $query->fetch()) {
                                echo '<tr>
                                        <td class="bordered-cell" scope="row">' . $rowNum . '</td> 
                                        <td class="bordered-cell">' . $row['nilai CPL'] . '</td>
                                        <td class="bordered-cell">' . $row['id_ikcpl'] . '</td>
                                        <td class="bordered-cell">' . $row['id_cpl'] . '</td>
                                        <td class="bordered-cell">' . $row['mk'] . '</td>
                                        <td class="bordered-cell">' . $row['NRP'] . '</td>
                                        <td class="bordered-cell">' . $row['tahun'] . '</td>
                                        <td class="bordered-cell">' . $row['angkatan'] . '</td>
                                    </tr>';
                                $rowNum++;
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <script>

            var sort = "ascending";
            function sortTable(n, id) {

                var table, rows, switching, i, x, y, shouldSwap;
                table = document.getElementById(id);
                switching = true;
                rows = table.getElementsByTagName("TR");
                // console.log(sort);
                for (i = 1; i < (rows.length - 1); i++) {
                    if (id == 'detail' && n == 1) {
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
                downloadCSV('Jumlah Mahasiswa.csv', 'mata_kuliah'); // Download first table
                downloadCSV('Detail Mahasiswa.csv', 'detail'); // Download second table
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