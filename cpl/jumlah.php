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
    <div class="col-md-3">
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
    </div>
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
            
        }   
    ?>

    <!-- isi -->
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p class="semester">Semester:
                    <?php echo $periode; ?><br>Angkatan:
                    <?php echo $angkatan; ?><br>Tahun:
                    <?php echo $tahun; ?>
                </p>
            </div>

        </div>
        <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nilai CPL</th>
                            <th scope="col">id ikcpl</th>
                            <th scope="col">id CPL</th>
                            <th scope="col">nilai</th>
                            <th scope="col">mk</th>
                            <!-- <th scope="col">Nama</th> -->
                            <th scope="col">NRPhash</th>
                            <th scope="col">Tahun</th>
                            <th scope="col">Angkatan</th>
                    
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            
                            $sql = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai_CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, kelas_nilaicpmk.nilai, mk.mk, mhsw.nrp_hash, periode.tahun,
                                        mhsw.tahun AS 'angkatan', periode.semester
                                    FROM kelas_cpmk
                                    JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                                    JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                                    JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                                    JOIN mk ON kelas.id_mk = mk.id_mk
                                    JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                                    JOIN periode ON kelas.id_periode = periode.id_periode  
                                    JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                                    GROUP BY mk.mk, kelas_nilaicpmk.nrp_hash
                                    HAVING nilai_CPL < 55.5";
                            
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
                            while($row = $query->fetch()) {
                                echo '<tr>
                                    <th scope="row">'.$rowNum.'</th> 
                                    <td>'.$row['nilai_CPL'].'</td>
                                    <td>'.$row['id_ikcpl'].'</td>
                                    <td>'.$row['id_cpl'].'</td>
                                    <td>'.$row['nilai'].'</td>
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
</body>

</html>