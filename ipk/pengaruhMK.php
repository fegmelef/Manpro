<?php
include("../api/connect.php");

if (isset($_GET["angkatan"])) {
    $angkatan = $_GET['angkatan'];
}

if (isset($_GET["tahun"])) {
    $tahun = $_GET['tahun'];
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
    <style>
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
                <li class="breadcrumb-item active">Data</li>
            </ul>
        </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
            <?php
               
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Mengambil nilai dropdown yang dipilih
                    $selectedValue = $_POST['filtering'];

                    // Membuat pernyataan if berdasarkan nilai dropdown
                    if ($selectedValue == 'Distribusi') {
                        header("location: ../ipk/distribusi_ips.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Data List') {
                        header("location: ../ipk/data_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Rata-rata IPK') {
                        header("location: ../ipk/rata2_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
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
                    <?php echo $angkatan; ?><br>Tahun:
                    <?php echo $tahun; ?>
                </p>
            </div>

            <div class="col-md-5">
                <form action="" method="post">
                    <select name="filtering" id="filtering" class="form-control1" onchange="redirectPage()">
                        <option value="selected value"><?php echo $val; ?></option>
                        <option value="Data List">Data List</option>
                        <option value="Distribusi">Distribusi</option>
                        <!-- <option value="Penuruan IPS">Jumlah</option> -->
                        <option value="Rata-rata IPK">Rata-rata</option>
                    </select>
                    <input type="submit" value="Kirim">
                </form>
                <button id="downloadCSV" onclick="downloadAllTables()">Download CSV</button>
            </div>
        </div>

    <div class="container">
        <h2>MK penyebab IPS turun</h2>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="turun">
                        <thead>
                            <tr> 
                                <th scope="col" onclick="sortTable(4,turun">Mata Kuliah</th>
                                <th scope="col" onclick="sortTable(1,'turun')">Nilai Rata-rata</th>
                                <th scope="col"onclick="sortTable(1)">Tahun</th>
                                <th scope="col"onclick="sortTable(2)">Semester</th>
                                <th scope="col" onclick="sortTable(3)">Angkatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                                <?php
                                    $query = "SELECT angkatan, tahun, semester, mk, MIN(rata) as nilai_terendah
                                    FROM rata_rata r1
                                    WHERE NOT EXISTS (
                                            SELECT *
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
                                        $query .= " AND tahun = :tahun";
                                    }

                                    if ($angkatan !== "All") {
                                        $query .= " AND angkatan = :angkatan";
                                    }

                                    $query .= " GROUP BY angkatan, tahun, semester, mk";
                                    $query .= " ORDER BY angkatan, tahun, semester, nilai_terendah";
                                    $query .= " LIMIT 3";

                                    $query = $conn->prepare($query);

                                    if ($periode !== "All") {
                                        $query->bindParam(':periode', $periode, PDO::PARAM_STR);
                                    }
                                    
                                    if ($angkatan !== "All") {
                                        $query->bindParam(':angkatan', $angkatan, PDO::PARAM_STR);
                                    }
                                    
                                    if ($tahun !== "All") {
                                        $query->bindParam(':tahun', $tahun, PDO::PARAM_STR);
                                    }
                                
                                    $query->execute();

                                    while($row = $query->fetch()) {
                                        echo '<tr>
                                            <td>'.$row['mk'].'</td>
                                            <td>'.$row['nilai_terendah'].'</td>
                                            <td>'.$row['tahun'].'</td>
                                            <td>'.$row['semester'].'</td>
                                            <td>'.$row['angkatan'].'</td>
                                            </tr>';
                                    }
                                ?>

                            </tbody>
                    </table>
                    </div>
                </div>

                <h2>MK penyebab IPS naik</h2>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table" id="naik">
                            <thead>
                                <tr> 
                                    <th scope="col">Mata Kuliah</th>
                                    <th scope="col">Nilai Rata-rata</th>
                                    <th scope="col">Tahun</th>
                                    <th scope="col">Semester</th>
                                    <th scope="col">Angkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query1 = "SELECT angkatan, tahun, semester, mk, MAX(rata) as nilai_tertinggi
                                    FROM rata_rata r1
                                    WHERE NOT EXISTS (
                                            SELECT *
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
                                        $query1 .= " AND tahun = :tahun";
                                    }

                                    if ($angkatan !== "All") {
                                        $query1 .= " AND angkatan = :angkatan";
                                    }

                                    $query1 .= " GROUP BY angkatan, tahun, semester, mk";
                                    $query1 .= " ORDER BY angkatan, tahun, semester, nilai_tertinggi DESC";
                                    $query1 .= " LIMIT 3";

                                    $query1 = $conn->prepare($query1);

                                    if ($periode !== "All") {
                                        $query1->bindParam(':periode', $periode, PDO::PARAM_STR);
                                    }
                                    
                                    if ($angkatan !== "All") {
                                        $query1->bindParam(':angkatan', $angkatan, PDO::PARAM_STR);
                                    }
                                    
                                    if ($tahun !== "All") {
                                        $query1->bindParam(':tahun', $tahun, PDO::PARAM_STR);
                                    }

                                    $query1->execute();

                                    while($row1 = $query1->fetch()) {
                                        echo '<tr>
                                            <td>'.$row1['mk'].'</td>
                                            <td>'.$row1['nilai_tertinggi'].'</td>
                                            <td>'.$row1['tahun'].'</td>
                                            <td>'.$row1['semester'].'</td>
                                            <td>'.$row1['angkatan'].'</td>
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
        function sortTable(n,id_tabel) {

            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById(id_tabel);
            switching = true;
            rows = table.getElementsByTagName("TR");
            console.log(sort);
            for (i = 1; i < (rows.length - 1); i++) {
                if (n==4){
                    max = rows[1].getElementsByTagName("TD")[1].textContent.toString();
                    min = "";
                }else{
                    max = 0;
                    min = Infinity;
                }

                for (j = i; j < (rows.length); j++) {
                    shouldSwap = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[j].getElementsByTagName("TD")[n];

                    if (n==0 || n==2){
                        xValue = parseInt(x.textContent.toString());
                        yValue = parseInt(y.textContent.toString());
                    }else{
                        xValue = x.textContent.toLowerCase();
                        yValue = y.textContent.toLowerCase();
                    }
                    
                    if(sort == "ascending"){
                        if (max < yValue) {
                            max = yValue;
                            index = j;
                        }
                    }else if (sort == "descending"){
                        if (min > yValue) {
                            min = yValue;
                            index = j;
                        }
                    }
                    
                }
                if (sort == "ascending") {
                    console.log(max);  
                    if (xValue <= max){
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                }else{
                    console.log(min);
                    if (xValue >= min){
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                }
            }
            if(sort == "ascending"){
                sort = "descending";
            }else{
                sort = "ascending";
            }
            console.log(rows)
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
