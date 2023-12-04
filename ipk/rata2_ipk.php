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
        <div class="col-md-9">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                <li class="breadcrumb-item active">Data</li>
            </ul>
        </div>
        <!-- <div class="col-md-3">
            <input type="text" placeholder="Search" name="search" class="search">
            <button type="submit" class="search"><i class="fa fa-search"></i></button>
            </form>
        </div> -->
    </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil nilai dropdown yang dipilih
        $selectedValue = $_POST['filtering'];

        // Membuat pernyataan if berdasarkan nilai dropdown
        if ($selectedValue == 'Distribusi') {
            header("location: ../ipk/distribusi_ips.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Pengaruh MK') {
            header("location: ../ipk/pengaruhmk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Data List') {
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
            <div class="col-md-7">
                <p class="semester">Semester:
                    <?php echo $periode; ?><br>Angkatan:
                    <?php echo $angkatan1; ?>-
                    <?php echo $angkatan2; ?><br>Tahun:
                    <?php echo $tahun; ?>-
                    <?php echo $tahun2; ?>
                </p>
            </div>

            <div class="col-md-5">
                <form action="" method="post">
                    <select name="filtering" id="filtering" class="form-control1" onchange="redirectPage()">
                        <option value="selected value">
                            <?php echo $val; ?>
                        </option>
                        <option value="Data List">Data List</option>
                        <option value="Distribusi">Distribusi</option>
                        <option value="Pengaruh MK">Pengaruh MK</option>
                        <!-- <option value="Rata-rata IPK">Rata-rata</option> -->
                        <option value="Rata-rata IPS">Rata-rata</option>
                    </select>
                    <input type="submit" value="Kirim">
                </form>
                <button id="downloadCSV" onclick="downloadCSV()">Download CSV</button>
            </div>
        </div>
        <!-- RATA-RATA CPL, BELOM BERDASARKAN TAHUN, ANGKATAN-->
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="Tabel_rata2">
                    <tr>

                        <th scope="col" onclick="sortTable(0)">Tahun</th>
                        <th scope="col" onclick="sortTable(1)">Angkatan</th>
                        <th scope="col" onclick="sortTable(2)">Semester</th>
                        <th scope="col" onclick="sortTable(3)">Rata-rata IPK</th>
                        <th scope="col" onclick="sortTable(4)">Rata-rata IPS</th>
                    </tr>
                    </thead>
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
                                <td>' . $row['tahun'] . '</td>
                                <td>' . $row['angkatan'] . '</td>
                                <td>' . $row['semester'] . '</td>
                                <td>' . $row['rata_ipk'] . '</td>
                                <td>' . $row['rata_ips'] . '</td>
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