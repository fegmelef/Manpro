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
                    else if ($selectedValue == 'Rata-rata IPS') {
                        header("location: ../ipk/rata2_ips.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
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
                        <option value="Rata-rata IPS">Rata-rata</option>
                    </select>
                    <input type="submit" value="Kirim">
                </form>
            </div>
        </div>

    <div class="container">
        <h2>MK penyebab IPS turun</h2>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
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
                                    $query = "SELECT angkatan, tahun, semester, mk, MIN(rata) as nilai_terendah
                                    FROM rata_rata r1
                                    WHERE angkatan = ?
                                        AND tahun = ?
                                        AND semester = ?
                                        AND NOT EXISTS (
                                            SELECT *
                                            FROM rata_rata r2
                                            WHERE r1.mk = r2.mk
                                                AND r2.semester < r1.semester
                                                AND r2.tahun <= r1.tahun
                                                AND r2.angkatan = r1.angkatan
                                        )
                                    GROUP BY angkatan, tahun, semester, mk
                                    ORDER BY angkatan, tahun, semester, nilai_terendah
                                    LIMIT 3";

                                    $query = $conn->prepare($query);
                                    $query->execute([$angkatan,$tahun,$periode]);

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
                                </div>
                                </body>
                                </html>
