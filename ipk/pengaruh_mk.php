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

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
    <style>
        body {
            overflow-x: hidden;
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
        <div class="col-md-3">
            <input type="text" placeholder="Search" name="search" class="search">
            <button type="submit" class="search"><i class="fa fa-search"></i></button>
            </form>
        </div>
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
        <h2>List Pengulangan MK</h2>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr> 
                                <th scope="col">Mata Kuliah</th>
                                <th scope="col">Jumlah Pengulangan</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Angkatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php
                                $query1 = "SELECT rata, mk, nilai, count, angkatan, tahun, semester
                                FROM `rata_rata`";

                                if ($periode !== "All") {
                                    $query1 .= " WHERE periode.semester = :periode";
                                }

                                if ($angkatan !== "All") {
                                    $query1 .= " WHERE mhsw.tahun = :angkatan";
                                }

                                if ($tahun !== "All") {
                                    $query1 .= " WHERE periode.tahun = :tahun";
                                }

                                $query2 = "SELECT * FROM `rata_rata`";

                                if ($angkatan !== "All") {
                                    $query2 .= " WHERE mhsw.tahun = :angkatan";
                                }
                                
                                $query1 .= " GROUP BY angkatan, mk";

                                $query1 = $conn->prepare($query1);
                                $query2 = $conn->prepare($query2);

                                $query1->execute();
                                $query2->execute();

                                $max = 1;
                                $worst_mk = " ";
                                $comp = 0;

                                // // Fetch all rows from $query2 into an array
                                // $rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);

                                while($row = $query1->fetch()) {
                                    $count = 0;
                                    $avg = 0;
                                    $query2->execute();
                                    // $rows = $query2->fetchAll(PDO::FETCH_ASSOC);

                                    // foreach ($rows as $row1) {
                                    //     if ($row1['mk'] == $row['mk']) {
                                    //         $count++;
                                    //         $avg += $row1['rata'];
                                    //     }
                                    while($row1 = $query2->fetch()) {
                                        if($row1['mk']==$row['mk']) {
                                            $count = $count + 1;
                                            $avg = $avg + $row['rata'];
                                        }
                                        if ($count>0){
                                            $avg = $avg/$count;
                                        }
                                    
                                        
                                    }
                                    echo '<td>'.$row['mk'].'</td><td>'.$count.'</td><td>'.$row['tahun'].'</td><td>'.$row['semester'].'</td>
                                    <td>'.$row['angkatan'].'</td></tr>';
                                    
                                    if ($count>$max) {
                                        $max = $count;
                                        $worst_mk = $row['mk'];
                                        $comp = $avg;
                                    }
                                    else if ($count==$max) {
                                        if ($comp<$avg) {
                                            $max = $max;
                                            $worst_mk = $worst_mk;
                                        }
                                        else if ($comp>$avg) {
                                            $max = $count;
                                            $worst_mk = $row['mk'];
                                        }
                                    }
                                }

                                echo '</tr></tbody></table></div></div>';

                                #list nilai MK
                                echo '<div class="container">
                                <h2>List Nilai MK</h2>
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
                                <tbody>';

                                $query1->execute();

                                while($row = $query1->fetch()) {
                                    echo        '<tr>
                                                <td>'.$row['mk'].'</td>
                                                <td>'.$row['rata'].'</td>
                                                <td>'.$row['tahun'].'</td>
                                                <td>'.$row['semester'].'</td>
                                                <td>'.$row['angkatan'].'</td>
                                                </tr>';
                                }
                                
                                echo '          
                                            </tbody>
                                            </table></div></div></div>';

                                #worst_mk
                                $query3 = "SELECT * FROM `rata_rata`
                                            WHERE  mk=?";

                                if ($periode !== "All") {
                                    $query3 .= " WHERE periode.semester = :periode";
                                }

                                if ($angkatan !== "All") {
                                    $query3 .= " WHERE mhsw.tahun = :angkatan";
                                }

                                if ($tahun !== "All") {
                                    $query3 .= " WHERE periode.tahun = :tahun";
                                }
                                // $query3 .= " GROUP BY angkatan";

                                $query3 = $conn->prepare($query3);
                                $query3->execute([$worst_mk]);
                                // $mk_down = $query3->fetch();

                                echo        '<div class="container">
                                            <h2>Mata Kuliah yang menyebabkan IPS turun</h2>
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
                                            <tbody>';
                                            
                                while($mk_down = $query3->fetch()){
                                    echo    '<tr>
                                            <td>'.$mk_down['mk'].'</td>
                                            <td>'.$mk_down['rata'].'</td>
                                            <td>'.$mk_down['tahun'].'</td>
                                            <td>'.$mk_down['semester'].'</td>
                                            <td>'.$mk_down['angkatan'].'</td>
                                            </tr>';
                                }

                                echo            '</tbody>
                                            </table></div></div></div>';
                            ?>
            
                            <?php
                                //best_mk
                                $query1 = "SELECT rata, mk, nilai, count, angkatan, tahun, semester
                                FROM `rata_rata`";

                                if ($periode !== "All") {
                                    $query1 .= " WHERE periode.semester = :periode";
                                }

                                if ($angkatan !== "All") {
                                    $query1 .= " WHERE mhsw.tahun = :angkatan";
                                }

                                if ($tahun !== "All") {
                                    $query1 .= " WHERE periode.tahun = :tahun";
                                }

                                $query2 = "SELECT * FROM `rata_rata`";

                                if ($angkatan !== "All") {
                                    $query2 .= " WHERE mhsw.tahun = :angkatan";
                                }
                                
                                // $query1 .= " GROUP BY angkatan";

                                $query1 = $conn->prepare($query1);
                                $query2 = $conn->prepare($query2);

                                $query1->execute();
                                $query2->execute();
                                
                                $min = 2;
                                $best_mk = " ";
                                $comp = 0;

                                // // Fetch all rows from $query2 into an array
                                // $rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);

                                // $rows = $query2->fetchAll(PDO::FETCH_ASSOC);

                                // foreach ($rows as $row1) {
                                //     if ($row1['mk'] == $row['mk']) {
                                //         $count++;
                                //         $avg += $row1['rata'];
                                //     }
                                while($row = $query1->fetch()) {
                                    $count = 0;
                                    $avg = 0;
                                    $query2->execute();
                                    while($row1 = $query2->fetch()) {
                                        if($row1['mk']==$row['mk']) {
                                            $count = $count + 1;
                                            $avg = $avg + $row['rata'];
                                        }
                                        if ($count>0){
                                            $avg = $avg/$count;
                                        }
                                        
                                    }
                                    if ($count<$min) {
                                        $min = $count;
                                        $best_mk = $row['mk'];
                                        $comp = $avg;
                                    }
                                    else if ($count==$min) {
                                        if ($comp>$avg) {
                                            $min = $min;
                                            $best_mk = $best_mk;
                                            $comp = $avg;
                                        }
                                        else if ($comp<$avg) {
                                            $min = $count;
                                            $best_mk = $row['mk'];
                                            $comp = $avg;
                                        }
                                    }
                                }

                                $query4 = "SELECT * FROM `rata_rata`
                                            WHERE mk=?";

                                if ($periode !== "All") {
                                    $query4 .= " WHERE periode.semester = :periode";
                                }

                                if ($angkatan !== "All") {
                                    $query4 .= " WHERE mhsw.tahun = :angkatan";
                                }

                                if ($tahun !== "All") {
                                    $query4 .= " WHERE periode.tahun = :tahun";
                                }

                                // $query4 .= " GROUP BY angkatan, tahun, semester";

                                $query4 = $conn->prepare($query4);
                                $query4->execute([$best_mk]);
                                // $rowCount = $query4->rowCount();
                                // echo $rowCount;
                                // $mk_up = $query4->fetch();
                                echo        '<div class="container">
                                            <h2>Mata Kuliah yang menyebabkan IPS naik</h2>
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
                                            <tbody>';
                                while($mk_up = $query4->fetch()){

                                    echo    '<tr>
                                            <td>'.$mk_up['mk'].'</td>
                                            <td>'.$mk_up['rata'].'</td>
                                            <td>'.$mk_up['tahun'].'</td>
                                            <td>'.$mk_up['semester'].'</td>
                                            <td>'.$mk_up['angkatan'].'</td>
                                            </tr>';
                                }
                                echo            '</tbody>
                                            </table></div></div></div>';
                                ?>
    </div>
</body>

</html>
