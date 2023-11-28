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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css.css">

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
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
                <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                <li class="breadcrumb-item active">Data</li>
            </ul>
        </div>
        <div class="col-md-3">
            <input type="text" placeholder="Search" name="search" class="search">
            <button type="submit" class="search"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
    <?php
        $kode_cpl = 'TF-01';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mengambil nilai dropdown yang dipilih
            $selectedValue = $_POST['filtering'];

            // Membuat pernyataan if berdasarkan nilai dropdown
            if ($selectedValue == 'Reporting') {
                header("location: ../cpl/reporting.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } 
            else if ($selectedValue == 'Data List') {
                header("location: ../cpl/data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } 
            else if ($selectedValue == 'Rata-rata') {
                header("location: ../cpl/rata_rata.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } 
            else if ($selectedValue == 'Distribusi Data') {
                header("location: ../cpl/distribusi.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } 

            
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
                        <option value="Distribusi Data">Distribusi Data</option>
                        <!-- <option value="Jumlah">Jumlah</option> -->
                        <option value="Rata-rata">Rata-rata</option>
                        <option value="Reporting">Reporting</option>
                    </select>
                    <input type="submit" value="Kirim">
                </form>
                <button id="downloadCSV" onclick="downloadAllTables()">Download CSV</button>
            </div>
        </div>

        <div class="row">
                <div class="col-md-12">
                    <table class="table" id="mata_kuliah">
                        <tr>
                            <th scope="col" onclick="sortTable(0, 'mata_kuliah')">No</th>
                            <th scope="col" onclick="sortTable(1,'mata_kuliah')">Mata Kuliah</th>
                            <th scope="col" onclick="sortTable(2, 'mata_kuliah')">Jumlah Mahasiswa Tidak Lulus</th>
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

                            if ($angkatan !== 'All'){
                                $query1 .= " AND angkatan = $angkatan";
                            } if ($periode !== 'All'){
                                $query1 .= " AND semester = $periode";
                            } if ($tahun !== 'All'){
                                $query1 .= " AND tahun = '$tahun'";
                            } 

                            $query1 .=") AS subquery GROUP BY subquery.mk";
                            $query1 = $conn->prepare($query1);
                            $query1->execute();

                            
                            $labels=[];
                            $values=[];
                            $rowNum = 1;
                            while($row1 = $query1->fetch()) {
                                $labels[]=$row1['Mata Kuliah'];
                                $values[]=$row1['Jumlah Mahasiswa Tidak Lulus'];
                                    echo '<tr>
                                        <td scope="row">'.$rowNum.'</td> 
                                        <td>'.$row1['Mata Kuliah'].'</td>
                                        <td>'.$row1['Jumlah Mahasiswa Tidak Lulus'].'</td>
                                    </tr>';

                                    $rowNum++; 
                                }                            
                            
                                ?>
                                
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row"> 
                <div class="col-md-12">
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
                                        label: 'Nilai CPL',
                                        data: value,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
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

        <div class="row">
                <div class="col-md-12">
                    <table class="table" id="detail">
                        <tr>
                            <th scope="col" onclick="sortTable(0, 'detail')">No</th>
                            <th scope="col" onclick="sortTable(1, 'detail')">Nilai CPL</th>
                            <th scope="col" onclick="sortTable(2, 'detail')">ID ikcpl</th>
                            <th scope="col" onclick="sortTable(3, 'detail')">ID CPL</th>
                            <th scope="col" onclick="sortTable(4, 'detail')">Mata Kuliah</th>
                            <th scope="col" onclick="sortTable(5, 'detail')">NRPhash</th>
                            <th scope="col" onclick="sortTable(6, 'detail')">Tahun</th>
                            <th scope="col" onclick="sortTable(7, 'detail')">Angkatan</th>
                    
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, mk.mk, mhsw.nrp_hash, periode.tahun, mhsw.tahun AS 'angkatan', periode.semester
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
                            
                            if ($angkatan !== 'All'){
                                $sql .= " AND angkatan = $angkatan";
                            } if ($periode !== 'All'){
                                $sql .= " AND semester = $periode";
                            } if ($tahun !== 'All'){
                                $sql .= " AND tahun = '$tahun'";
                            } 
                                   
                            $sql .= ' ORDER BY `mhsw`.`nrp_hash` ASC';

                            $query = $conn->prepare($sql);
                            $query->execute();
                        
                            $rowNum = 1;
                            while ($row = $query->fetch()) {
                                    echo '<tr>
                                        <td scope="row">'.$rowNum.'</td> 
                                        <td>'.$row['nilai CPL'].'</td>
                                        <td>'.$row['id_ikcpl'].'</td>
                                        <td>'.$row['id_cpl'].'</td>
                                        <td>'.$row['mk'].'</td>
                                        <td>'.$row['nrp_hash'].'</td>
                                        <td>'.$row['tahun'].'</td>
                                        <td>'.$row['angkatan'].'</td>
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
        function sortTable(n,id) {

            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById(id);
            switching = true;
            rows = table.getElementsByTagName("TR");
            // console.log(sort);
            for (i = 1; i < (rows.length - 1); i++) {
                if (id == 'detail' && n ==1){
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
                    // console.log(max);  
                    if (xValue <= max){
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                }else{
                    // console.log(min);
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