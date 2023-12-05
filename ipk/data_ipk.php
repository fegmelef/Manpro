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
                <li class="breadcrumb-item active">List Data</li>
            </ul>
        </div>
    </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil nilai dropdown yang dipilih
        $selectedValue = $_POST['filtering'];
        if ($selectedValue == 'Distribusi') {
            header("location: ../ipk/distribusi_ips.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Pengaruh MK') {
            header("location: ../ipk/pengaruhMK.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Rata-rata IPK') {
            header("location: ../ipk/rata2_ipk.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode&val=$selectedValue");
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
                    </span>-<span>
                        <?php echo $tahun2; ?>
                    </span></p>
            </div>

            <div class="col-md-4 col-xs-4">
                <form action="" method="post">
                    <div class="col-md-10 col-xs-10">
                        <select name="filtering" id="filtering" class="form-control" onchange="redirectPage()">
                        
                        <option value="Data List">Data List</option>
                        <option value="Distribusi">Distribusi</option>
                        <option value="Pengaruh MK">Pengaruh MK</option>
                        <option value="Rata-rata IPK">Rata-rata</option>
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


        <div class="row">
                <div class="col-md-12 col-xs-12">
                    <table class="table" id="tabel_ipk">
                        <tr>
                            <th class="bordered-header" scope="col" onclick="sortTable(0)">No</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(1)">NRP</th>
                            <th class="bordered-header" scope="col" onclick="sortTable(2)">Tahun</th>
                            <th class="bordered-header" scope="col"> </th>
                        </tr>
                        <tbody>
                        <?php
                        // $query = $conn->prepare("SELECT * FROM mhsw WHERE mhsw.tahun = $angkatan ");
                        $sql = "SELECT * FROM mhsw";

                        if ($angkatan1 !== 'All') {
                            $sql .= " WHERE mhsw.tahun between '$angkatan1' and '$angkatan2'";
                        }

                            $query = $conn->prepare($sql);
                            $query->execute();
                            $rowNum = 1;
                            while($row = $query->fetch()) { ?>
                                <tr>
                                <td class="bordered-cell" scope="row"><?php echo $rowNum; ?></td>
                                <td class="bordered-cell"><?php echo $row['nrp_hash'];?></td>
                                <td class="bordered-cell"><?php echo $row['tahun'];?></td>
                                <td class="bordered-cell"><form method="post" action="detail_ipk.php?angkatan=<?php echo $angkatan1; ?>&tahun=<?php echo $tahun; ?>&periode=<?php echo $periode; ?>">
                                <input type="hidden" name="nrp" value="<?php echo $row['nrp_hash'];?>">
                                <input type="hidden" name="year" value="<?php echo $row['tahun'];?>">
                                <button type="submit" name="detail" class="btn btn-dark">Detail</button>
                                </form></td>
                            </tr>
                            <?php
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
        function sortTable(n) {

            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById("tabel_ipk");
            switching = true;
            rows = table.getElementsByTagName("TR");
            // console.log(sort);
            for (i = 1; i < (rows.length - 1); i++) {
                if (n == 1) {
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

        //DOWNLOAD CSV
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
                    link.setAttribute('download', 'data_ipk.csv');
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