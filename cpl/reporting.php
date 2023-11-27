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
        </style>
    </head>
    <body>
        <!-- navbar -->
        <?php include "../navbar/navbar_after_login.php";?>

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
            
            if (isset($_POST['filtering'])) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Mengambil nilai dropdown yang dipilih
                    $selectedValue = $_POST['filtering'];
    
                    // Membuat pernyataan if berdasarkan nilai dropdown
                    if ($selectedValue == 'Data List') {
                        header("location: ../cpl/data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode");
                        exit;
                    }  
                    else if ($selectedValue == 'Rata-rata') {
                        header("location: ../cpl/rata_rata.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                     else if ($selectedValue == 'Jumlah') {
                        header("location: ../cpl/jumlah.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                }    else if ($selectedValue == 'Distribusi Data') {
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
                            <option value="Jumlah">Jumlah</option>
                            <option value="Rata-rata">Rata-rata</option>
                            <option value="Reporting">Reporting</option>
                        </select>
                        <input type="submit" value="Kirim">
                    </form>
                    <button id="downloadCSV" onclick="downloadCSV()">Download CSV</button>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <form method="post" action="">
                        <select name="filtering-CPL" id="filtering-CPL" class="form-control1">
                            <option value="All_CPL">All</option>
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
                        <input type="submit" value="Kirim">
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">NRP hash</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Nilai</th>
                            <th scope="col">CPL</th>
                            <th scope="col">Tahun</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $selectedValue = 'ALL_CPL';
                            $kode_cpl = 'TF-01' ;
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                // Mengambil nilai dropdown yang dipilih
                                // $selectedValue = $_POST['filtering-CPL'];

                                // Membuat pernyataan if berdasarkan nilai dropdown
                                if ($selectedValue == 'TF-01') {
                                    $kode_cpl = 'TF-01';
                                } elseif ($selectedValue == 'TF-02') {
                                    $kode_cpl = 'TF-02';
                                } elseif ($selectedValue == 'TF-03') {
                                    $kode_cpl = 'TF-03';
                                } elseif ($selectedValue == 'TF-04') {
                                    $kode_cpl = 'TF-04';
                                } elseif ($selectedValue == 'TF-05') {
                                    $kode_cpl = 'TF-05';
                                } elseif ($selectedValue == 'TF-06') {
                                    $kode_cpl = 'TF-06';
                                } elseif ($selectedValue == 'TF-07') {
                                    $kode_cpl = 'TF-07';
                                } elseif ($selectedValue == 'TF-08') {
                                    $kode_cpl = 'TF-08';
                                } elseif ($selectedValue == 'TF-09') {
                                    $kode_cpl = 'TF-09';
                                } elseif ($selectedValue == 'TF-10') {
                                    $kode_cpl = 'TF-10';
                                } 
                            }   

                            if ($selectedValue == "ALL_CPL"){
                                $sql = "SELECT kn.*, ikcpl.id_cpl, m.tahun, m.nama
                                FROM kelas_nilaicpmk kn
                                JOIN kelas_cpmk kc ON kn.id_cpmk = kc.id_cpmk 
                                JOIN ikcpl ON ikcpl.id_ikcpl = kc.id_ikcpl 
                                JOIN mhsw m ON kn.nrp_hash = m.nrp_hash
                                WHERE kn.nilai < (
                                    SELECT AVG(sub.nilai)
                                    FROM kelas_nilaicpmk sub
                                    JOIN kelas_cpmk subkc ON sub.id_cpmk = subkc.id_cpmk 
                                    JOIN ikcpl subik ON subik.id_ikcpl = subkc.id_ikcpl 
                                    JOIN mhsw submhsw ON sub.nrp_hash = submhsw.nrp_hash
                                    WHERE subik.id_ikcpl = ikcpl.id_ikcpl
                                )";
                            }
                            else{
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
                            }
                            $query = $conn->prepare($sql);
                            $query->execute();
                            $rowNum = 1; 
                            while($row = $query->fetch()) {
                                echo '<tr>
                                <th scope="row">'.$rowNum.'</th>
                                <td>'.$row['nrp_hash'].'</td>
                                <td>'.$row['nama'].'</td>
                                <td>'.$row['nilai'].'</td>
                                <td>'.$row['id_cpl'].'</td>
                                <td>'.$row['tahun'].'</td>
                            </tr>';
                            $rowNum++; 
                            }
                        ?>
                        
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>           
        </div>


<script>
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