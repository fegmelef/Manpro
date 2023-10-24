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
            <option value="Distribusi Data">Rata-rata</option>
            <option value="Jumlah">Jumlah</option>
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
            else if ($selectedValue == 'Distribusi Data') {
                header("location: ../cpl/distribusi.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } 
        }   
    ?>

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
    
    <div class="col-md-12">
    <table border="1" style="width:100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama MK</th>
                <th>CPL 1</th>
                <th>CPL 2</th>
                <th>CPL 3</th>
                <th>CPL 4</th>
                <th>CPL 5</th>
                <th>CPL 6</th>
                <th>CPL 7</th>
                <th>CPL 8</th>
                <th>CPL 9</th>
                <th>CPL 10</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Row 1</th>
                <td>TF</td>
                <td>Struktur Data</td>
                <td>Data 1,1</td>
                <td>Data 1,2</td>
                <td>Data 1,3</td>
                <td>Data 1,4</td>
                <td>Data 1,5</td>
                <td>Data 1,6</td>
                <td>Data 1,7</td>
                <td>Data 1,8</td>
                <td>Data 1,9</td>
                <td>Data 1,10</td>
            </tr>
            <tr>
                <th>Row 2</th>
                <td>Data 2,1</td>
                <td>Data 2,2</td>
                <td>Data 2,3</td>
            </tr>
            <tr>
                <th>Row 3</th>
                <td>Data 3,1</td>
                <td>Data 3,2</td>
                <td>Data 3,3</td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
    </body>
    </html>