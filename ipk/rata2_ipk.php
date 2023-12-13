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
                <li class="breadcrumb-item active">Rata-rata IPS dan IPK</li>
            </ul>
        </div>
    </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil nilai dropdown yang dipilih
        $selectedValue = $_POST['filtering'];

        // Membuat pernyataan if berdasarkan nilai dropdown
        if ($selectedValue == 'Distribusi IPS dan IPK') {
            header("location: ../ipk/distribusi_ips.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Pengaruh MK Terhadap IPS') {
            header("location: ../ipk/pengaruhmk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'List Data') {
            header("location: ../ipk/data_ipk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        }


        // else if ($selectedValue == 'Jumlah') {
        //     header("location: ../ipk/jumlah_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
        //     exit;
        // } 
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
                    </span>-<span>
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
                            <option value="ListbData">List Data</option>
                            <option value="Distribusi IPS dan IPK">Distribusi IPS dan IPK</option>
                            <option value="Pengaruh MK Terhadap IPS">Pengaruh MK Terhadap IPS</option>
                            <!-- <option value="Rata-rata IPK">Rata-rata</option> -->
                        </select>
                    </div>
                    
                    <div class="col-md-2 col-xs-2">
                        <input type="submit" value="Kirim" class="btn btn-primary">
                    </div>
                </form>
            </div>

            <div class="col-md-1 col-xs-1">
                <div class="col-md-1 col-xs-1">
                    <svg id="downloadCSV" onclick="downloadCSV()" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="cursor: pointer;">
                        <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                    </svg>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script> -->
        <div style="width: 100%;height: 100%">
            <canvas id="chartIPK"></canvas>
        </div>

        <!-- RATA-RATA CPL, BELOM BERDASARKAN TAHUN, ANGKATAN-->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <table class="table" id="Tabel_rata2">
                    <tr>
                        <th class="bordered-header" scope="col" onclick="sortTable(0)">Tahun</th>
                        <th class="bordered-header" scope="col" onclick="sortTable(1)">Angkatan</th>
                        <th class="bordered-header" scope="col" onclick="sortTable(2)">Semester</th>
                        <th class="bordered-header" scope="col" onclick="sortTable(3)">Rata-rata IPK</th>
                        <th class="bordered-header" scope="col" onclick="sortTable(4)">Rata-rata IPS</th>
                    </tr>
                    <tbody>
                        <?php
                        $sql = "SELECT ipk.tahun, ipk.angkatan, ipk.semester, AVG(ipk.ipk) AS rata_ipk, AVG(ips.ips) AS rata_ips
                        FROM ipk
                        LEFT JOIN ips ON ipk.tahun = ips.tahun AND ipk.angkatan = ips.angkatan AND ipk.semester = ips.semester
                        WHERE ipk.ipk IS NOT NULL";

                        if ($periode !== "All") {
                            $sql .= " AND ipk.semester = :periode";
                        }

                        if ($angkatan1 !== "All") {
                            $sql .= " AND ipk.angkatan between :angkatan1 and :angkatan2";
                        }

                        if ($tahun !== "All") {
                            $sql .= " AND ipk.tahun >= :tahun and ipk.tahun <= :tahun2";
                        }

                        $sql .= " GROUP BY ipk.tahun, ipk.angkatan, ipk.semester";
                        $sql .= " ORDER BY ipk.tahun";

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
                                <td class="bordered-cell">' . $row['tahun'] . '</td>
                                <td class="bordered-cell">' . $row['angkatan'] . '</td>
                                <td class="bordered-cell">' . $row['semester'] . '</td>
                                <td class="bordered-cell">' . $row['rata_ipk'] . '</td>
                                <td class="bordered-cell">' . $row['rata_ips'] . '</td>
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
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
        var table = document.getElementById('Tabel_rata2');
        var datal = {}; // Ubah ke objek untuk mengelompokkan datal
        var datas = {}; // Ubah ke objek untuk mengelompokkan datal
        var colorMap = {}; // Menyimpan warna untuk setiap angkatan


        // Loop melalui setiap baris tabel untuk mengambil datal
        for (var i = 1; i < table.rows.length; i++) {
            var row = table.rows[i];
            var year = row.cells[0].innerText;
            var semester = row.cells[2].innerText;
            var angkatan = row.cells[1].innerText;
            var averageIPK = parseFloat(row.cells[3].innerText);

            // Inisialisasi objek jika belum ada
            if (!datal[year]) {
                datal[year] = {};
            }
            if (!datal[year][semester]) {
                datal[year][semester] = {};
            }
            if (!datal[year][semester][angkatan]) {
                datal[year][semester][angkatan] = [];
                // Inisialisasi warna untuk setiap angkatan jika belum ada
                if (!colorMap[angkatan]) {
                    colorMap[angkatan] = randomColor();
                }
            }

            // Masukkan nilai IPK ke dalam array
            // datal[year][semester][angkatan].push(averageIPK || 0);
            
            if (!datas[angkatan]) {
                datas[angkatan] = {};
            }
            if (!datas[angkatan][year]) {
                datas[angkatan][year] = {};
            }
            if (!datas[angkatan][year][semester]) {
                datas[angkatan][year][semester] = [];
                // Inisialisasi warna untuk setiap angkatan jika belum ada
                // if (!colorMap[angkatan]) {
                //     colorMap[angkatan] = randomColor();
                // }
            }

            // Masukkan nilai IPK ke dalam array
            datas[angkatan][year][semester].push(averageIPK);
            // datas[year][semester][angkatan].push(averageIPK);
        }
        console.log(datas);
        var labels = [];
        var datasets = {};

        for (var angkatan in datas) {
            semesterData_a=[];
            for (var year in datal) {
            
                for (var semester in datas[angkatan][year]) {
                    
                    // if(datas[angkatan][year][semester].length > 0){
                        var semesterData = datas[angkatan][year][semester];
                    // }
                    // else{
                        // var semesterData = 0;
                    // }
                    
                    semesterData_a.push(semesterData);
                }
            }
            var dataset = {
                        label: angkatan,
                        data: semesterData_a,
                        backgroundColor: colorMap[angkatan],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    };
                    datasets[angkatan] = dataset; // Menyimpan dataset untuk setiap angkatan
                
        console.log(datasets);
        }

        for (var year in datal) {
            for (var semester in datal[year]) {
                var label = year +'-'+semester;
                labels.push(label);
            }
        }
        
        var convertedDatasets = Object.values(datasets).map(function (dataset) {
        return dataset;
    });
    console.log(convertedDatasets);
    console.log(labels);

        var ctx = document.getElementById('chartIPK').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, //labels sudah benar grouping berdasarkan tahun dan semester
                datasets: convertedDatasets //kalau mau dipisah arraynya juga dipisah(?) 1. coba pakai cara pisah array
            },
            // data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    function randomColor() {
        return 'rgba(' +
            Math.floor(Math.random() * 256) + ',' +
            Math.floor(Math.random() * 256) + ',' +
            Math.floor(Math.random() * 256) + ', 0.6)';
    }

        var sort = "ascending";
        function sortTable(n) {

            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById("Tabel_rata2");
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

        function downloadCSV() {
            var table = document.querySelector('table'); // Get the table element
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
                navigator.msSaveBlob(blob, 'table.csv');
            } else {
                // For other browsers
                var link = document.createElement('a');
                if (link.download !== undefined) {
                    var url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'Rata Rata IPK.csv');
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