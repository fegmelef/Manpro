<?php
    include("../api/connect.php");
    // include("../api/get_data.php");

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
        <?php include "../navbar/navbar_after_login.php";?>

        <!-- bread crumbs -->
        <div class="row">
            <div class="col-md-9 col-xs-9">
                <ul id="breadcrumb" class="breadcrumb">                        
                    <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                    <li class="breadcrumb-item active">Daftar Mahasiswa Dibawah Rata-rata Nilai</li>                    
                </ul>
            </div>
        </div>

        <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
        <?php            
            if (isset($_POST['filtering'])) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Mengambil nilai dropdown yang dipilih
                    $selectedValue = $_POST['filtering'];
    
                    // Membuat pernyataan if berdasarkan nilai dropdown
                    if ($selectedValue == 'List Data') {
                        header("location: ../cpl/data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode");
                        exit;
                    }  
                    else if ($selectedValue == 'Rata-rata Nilai') {
                        header("location: ../cpl/rata_rata.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Jumlah Mahasiswa Mengulang MK') {
                        header("location: ../cpl/jumlah.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Distribusi Nilai') {
                        header("location: ../cpl/distribusi.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                    exit;
                    }    
                }
            }  
        ?>

        <!-- isi -->
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-xs-7">
                    <p class="semester">Semester <span><?php echo $periode; ?></span> || Angkatan <span><?php echo $angkatan; ?></span> || Tahun <span><?php echo $tahun; ?></span></p>
                </div>

                <div class="col-md-4 col-xs-4">
                    <form action="" method="post">
                        <div class="col-md-10 col-xs-10">
                            <select name="filtering" id="filtering" class="form-control" onchange="redirectPage()">
                                <option value="selected value"><?php echo $val; ?></option>
                                <option value="List Data">List Data</option>
                                <option value="Distribusi Nilai">Distribusi Nilai</option>
                                <option value="Jumlah Mahasiswa Mengulang MK">Jumlah Mahasiswa Mengulang MK</option>
                                <option value="Rata-rata Nilai">Rata-rata Nilai</option>
                                <!-- <option value="Daftar Mahasiswa Dibawah Rata-rata Nilai">Daftar Mahasiswa Dibawah Rata-rata Nilai</option> -->
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
        </div>

        <div class="container">
            <div class="row">                    
                <?php
                    $kode_cpl = 'TF-01' ;
                    if (isset($_POST['filtering-CPL'])) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Mengambil nilai dropdown yang dipilih
                            $selectedValue1 = $_POST['filtering-CPL'];

                            // Membuat pernyataan if berdasarkan nilai dropdown
                            if ($selectedValue1 == 'TF-01') {
                                $kode_cpl = "TF-01";
                            } elseif ($selectedValue1 == 'TF-02') {
                                $kode_cpl = "TF-02";
                            } elseif ($selectedValue1 == 'TF-03') {
                                $kode_cpl = 'TF-03';
                            } elseif ($selectedValue1 == 'TF-04') {
                                $kode_cpl = 'TF-04';
                            } elseif ($selectedValue1 == 'TF-05') {
                                $kode_cpl = 'TF-05';
                            } elseif ($selectedValue1 == 'TF-06') {
                                $kode_cpl = 'TF-06';
                            } elseif ($selectedValue1 == 'TF-07') {
                                $kode_cpl = 'TF-07';
                            } elseif ($selectedValue1 == 'TF-08') {
                                $kode_cpl = 'TF-08';
                            } elseif ($selectedValue1 == 'TF-09') {
                                $kode_cpl = 'TF-09';
                            } elseif ($selectedValue1 == 'TF-10') {
                                $kode_cpl = 'TF-10';
                            } 
                        }   
                    }
                ?>

                <div class="col-md-7 col-xs-7">
                    <p class="TF"><span><?php echo isset($selectedValue1) ? $selectedValue1 : ' '; ?></span></p>
                </div>


                <div class="col-md-4 col-xs-4">
                    <form action="" method="post">
                        <div class="col-md-10 col-xs-10">
                            <select name="filtering-CPL" id="filtering-CPL" class="form-control"> 
                                <option value="" disabled selected>Pilih CPL</option>
                                <option value="TF-01">TF-01</option>
                                <option value="TF-02">TF-02</option>
                                <option value="TF-03">TF-03</option>
                                <option value="TF-04">TF-04</option>
                                <option value="TF-05">TF-05</option>
                                <option value="TF-06">TF-06</option>
                                <option value="TF-07">TF-07</option>
                                <option value="TF-08">TF-08</option>
                                <option value="TF-09">TF-09</option>
                                <option value="TF-10">TF-10</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-xs-2">
                            <input type="submit" value="Kirim" class="btn btn-primary">
                        </div>
                    </form>
                </div>
              
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <?php
                            // $selectedValue = 'ALL_CPL';
                            $kode_cpl = 'TF-01' ;
                            if(isset($_POST['filtering-CPL'])){
                            ?>
                                <table class="table" id="tabel_reporting_cpl">
                                    <tr>
                                        <th class="bordered-header" scope="col" onclick="sortTable(0)">No</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(1)">NRP hash</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(2)">Nilai</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(3)">CPL</th>
                                        <th class="bordered-header" scope="col" onclick="sortTable(4)">Tahun</th>
                                    </tr>
                                    <tbody>
                                    <?php
                                        $sql = "SELECT kelas_nilaicpmk.*, ikcpl.id_cpl, mhsw.tahun, mhsw.nama
                                        FROM kelas_nilaicpmk
                                        JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk 
                                        JOIN ikcpl ON ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl 
                                        JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                                        WHERE ikcpl.id_cpl = '$kode_cpl' 
                                        AND kelas_nilaicpmk.nilai < (
                                            SELECT AVG(nilai) 
                                            FROM kelas_nilaicpmk 
                                            JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk 
                                            JOIN ikcpl ON ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl 
                                            JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                                            JOIN periode ON kelas.id_periode = periode.id_periode
                                            JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                                            WHERE ikcpl.id_cpl = '$kode_cpl'";

                                        if ($angkatan !== 'All'){
                                            $sql .= " AND mhsw.tahun = $angkatan";
                                        }
                                        if ($periode !== 'All'){
                                            $sql .= " AND periode.semester = $periode";
                                        }
                                        if ($tahun !== 'All'){
                                            $sql .= " AND periode.tahun = '$tahun'";
                                        }
                                        
                                        $sql .= " GROUP BY ikcpl.id_cpl )";

                                        if ($angkatan !== 'All'){
                                            $sql .= " AND mhsw.tahun = $angkatan";
                                        }

                                        $query = $conn->prepare($sql);

                                        $query->execute();
                                        $rowNum = 1; 
                                        while($row = $query->fetch()) {
                                            echo '<tr>
                                            <td class="bordered-cell" scope="row">'.$rowNum.'</td>
                                            <td class="bordered-cell">'.$row['nrp_hash'].'</td>
                                            <td class="bordered-cell">'.$row['nilai'].'</td>
                                            <td class="bordered-cell">'.$row['id_cpl'].'</td>
                                            <td class="bordered-cell">'.$row['tahun'].'</td>
                                        </tr>';
                                        $rowNum++; 
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                        ?> 
                    </div>
                </div>    
            </div>           
        </div>


    <script>
        var sort = "ascending";
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById("tabel_reporting_cpl");
            switching = true;
            rows = table.getElementsByTagName("TR");
            // console.log(sort);
            for (i = 1; i < (rows.length - 1); i++) {
                if (n==1){
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
        function downloadCSV() {
            var table = document.querySelector('table'); // Get the table element
            var rows = Array.from(table.querySelectorAll('tr')); // Get all rows in the table
            
            // Create a CSV content string
            var csvContent = rows.map(function(row) {
                var rowData = Array.from(row.querySelectorAll('th, td'))
                    .map(function(cell) {
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
                    link.setAttribute('download', 'tableReporting.csv');
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