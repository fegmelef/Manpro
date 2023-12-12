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
        <style>
            body {
                overflow-x: hidden;
            }
            th {
            cursor: pointer;
            };
        </style>
    </head>

<body>
    <!-- navbar -->
    <?php include "../navbar/navbar_after_login.php"; ?>


    <!-- bread crumbs -->
    <div class="row">
        <div class="col-md-9">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_ipk.php">Home</a></li>
                <li class="breadcrumb-item active">Pengaruh MK Terhadap IPS</li>
            </ul>
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
            } else if ($selectedValue == 'List Data') {
                header("location: ../ipk/data_ipk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'Rata-rata IPS dan IPK') {
                header("location: ../ipk/rata2_ipk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
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
                                    <option value="selected value"><?php echo $val; ?></option>
                                    <option value="Data List">List Data</option>
                                    <option value="Distribusi">Distribusi IPS dan IPK</option>
                                    <!-- <option value="Penuruan IPS">Jumlah</option> -->
                                    <option value="Rata-rata IPK">Rata-rata IPS dan IPK</option>
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
            </div>

        <div class="container">
            <h2>MK penyebab IPS turun</h2>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <table class="table" id="turun">
                                <tr> 
                                    <th class="bordered-header" scope="col" onclick="sortTable(0,'turun')">Mata Kuliah</th>
                                    <th class="bordered-header" scope="col" onclick="sortTable(1,'turun')">Nilai Rata-rata</th>
                                    <th class="bordered-header" scope="col"onclick="sortTable(2,'turun')">Tahun</th>
                                    <th class="bordered-header" scope="col"onclick="sortTable(3,'turun')">Semester</th>
                                    <th class="bordered-header" scope="col" onclick="sortTable(4,'turun')">Angkatan</th>
                                </tr>
                            <tbody>
                                <?php
                                $query = "WITH RankedMataKuliah AS (
                                        SELECT
                                            angkatan,
                                            tahun,
                                            semester,
                                            mk,
                                            MIN(rata) as nilai_terendah,
                                            ROW_NUMBER() OVER (PARTITION BY angkatan ORDER BY MIN(rata)) AS row_num
                                        FROM
                                            rata_rata r1
                                        WHERE NOT EXISTS (
                                            SELECT 1
                                            FROM rata_rata r2
                                            WHERE r1.mk = r2.mk
                                                AND r2.semester < r1.semester
                                                AND r2.tahun <= r1.tahun
                                                AND r2.angkatan = r1.angkatan
                                        )";


                                if ($periode !== "All") {
                                    $query .= " AND semester = :periode";
                                }

                                if ($tahun !== "All") {
                                    $query .= " AND tahun >= :tahun and tahun <=:tahun2";
                                }

                                if ($angkatan1 !== "All") {
                                    $query .= " AND angkatan between :angkatan1 and :angkatan2";
                                }

                                $query .= " GROUP BY angkatan, tahun, semester, mk
                                    )
                                    SELECT
                                        angkatan,
                                        tahun,
                                        semester,
                                        mk,
                                        nilai_terendah
                                    FROM
                                        RankedMataKuliah
                                    WHERE
                                        row_num <= 3
                                    ORDER BY
                                        angkatan, nilai_terendah;";

                                $query = $conn->prepare($query);

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

                                while ($row = $query->fetch()) {
                                    echo '<tr>
                                            <td class="bordered-cell">' . $row['mk'] . '</td>
                                            <td class="bordered-cell">' . $row['nilai_terendah'] . '</td>
                                            <td class="bordered-cell">' . $row['tahun'] . '</td>
                                            <td class="bordered-cell">' . $row['semester'] . '</td>
                                            <td class="bordered-cell">' . $row['angkatan'] . '</td>
                                            </tr>';
                                }
                                ?>
                                </tbody>
                                </table>
                        </div>
                    </div>

                    <h2>MK penyebab IPS naik</h2>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <table class="table" id="naik">
                                    <tr>
                                        <th class="bordered-header" scope="col" onclick="sortTable(0,'naik')">Mata Kuliah</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(1,'naik')">Nilai Rata-rata</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(2,'naik')">Tahun</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(3,'naik')">Semester</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(4,'naik')">Angkatan</th>
                                    </tr>
                                <tbody>
                                    <?php
                                    $query1 = "WITH RankedMataKuliah AS (
                                            SELECT
                                                angkatan,
                                                tahun,
                                                semester,
                                                mk,
                                                MAX(rata) as nilai_tertinggi,
                                                ROW_NUMBER() OVER (PARTITION BY angkatan ORDER BY MAX(rata) DESC) AS row_num
                                            FROM
                                                rata_rata r1
                                            WHERE NOT EXISTS (
                                                SELECT 1
                                                FROM rata_rata r2
                                                WHERE r1.mk = r2.mk
                                                    AND r2.semester < r1.semester
                                                    AND r2.tahun <= r1.tahun
                                                    AND r2.angkatan = r1.angkatan
                                            )";

                                if ($periode !== "All") {
                                    $query1 .= " AND semester = :periode";
                                }

                                if ($tahun !== "All") {
                                    $query1 .= " AND tahun >= :tahun and tahun <= :tahun2";
                                }

                                if ($angkatan1 !== "All") {
                                    $query1 .= " AND angkatan between :angkatan1 and :angkatan2";
                                }

                                $query1 .= " GROUP BY angkatan, tahun, semester, mk
                                    )
                                    SELECT
                                        angkatan,
                                        tahun,
                                        semester,
                                        mk,
                                        nilai_tertinggi
                                    FROM
                                        RankedMataKuliah
                                    WHERE
                                        row_num <= 3
                                    ORDER BY
                                        angkatan, nilai_tertinggi DESC";

                                $query1 = $conn->prepare($query1);

                                if ($periode !== "All") {
                                    $query1->bindParam(':periode', $periode, PDO::PARAM_STR);
                                }

                                if ($angkatan1 !== "All") {
                                    $query1->bindParam(':angkatan1', $angkatan1, PDO::PARAM_STR);
                                }
                                if ($angkatan2 !== "All") {
                                    $query1->bindParam(':angkatan2', $angkatan2, PDO::PARAM_STR);
                                }

                                if ($tahun !== "All") {
                                    $query1->bindParam(':tahun', $tahun, PDO::PARAM_STR);
                                }
                                if ($tahun2 !== "All") {
                                    $query1->bindParam(':tahun2', $tahun2, PDO::PARAM_STR);
                                }
                                $query1->execute();

                                    while ($row1 = $query1->fetch()) {
                                        echo '<tr>
                                                <td class="bordered-cell">' . $row1['mk'] . '</td>
                                                <td class="bordered-cell">' . $row1['nilai_tertinggi'] . '</td>
                                                <td class="bordered-cell">' . $row1['tahun'] . '</td>
                                                <td class="bordered-cell">' . $row1['semester'] . '</td>
                                                <td class="bordered-cell">' . $row1['angkatan'] . '</td>
                                                </tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <script>

                var sort = "ascending";
                function sortTable(n, id_tabel) {

                    var table, rows, switching, i, x, y, shouldSwap;
                    table = document.getElementById(id_tabel);
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
                    downloadCSV('Pengaruh MK Turun.csv', 'turun'); // Download first table
                    downloadCSV('Pengaruh MK Naik.csv', 'naik'); // Download second table
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