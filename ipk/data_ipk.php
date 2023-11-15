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
            <?php
                $kode_cpl = 'TF-01';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Mengambil nilai dropdown yang dipilih
                    $selectedValue = $_POST['filtering'];

                    // Membuat pernyataan if berdasarkan nilai dropdown
                    if ($selectedValue == 'Jumlah') {
                        header("location: ../ipk/jumlah_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Penurunan IPS') {
                        header("location: ../ipk/penurunan_ips.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
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
                        <option value="Data List">Data List</option>
                        <option value="Jumlah">Jumlah</option>
                        <option value="Penurunan IPS">Jumlah</option>
                        <option value="Rata-rata IPK">Rata-rata</option>
                        <option value="Rata-rata IPS">Reporting</option>
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
                            <!-- <th scope="col">Nama</th> -->
                            <th scope="col">NRP</th>
                            <th scope="col">Tahun</th>
                            <th scope="col"> </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            // $query = $conn->prepare("SELECT * FROM mhsw WHERE mhsw.tahun = $angkatan ");
                            $sql = "SELECT * FROM mhsw";

                            if ($angkatan !== 'All'){
                                $sql .= " WHERE mhsw.tahun = '$angkatan'";
                            }

                            $query = $conn->prepare($sql);

                            // $twodigit = $angkatan % 100;
                            // $query->execute([$twodigit]);
                            
                            $query->execute();
                            $rowNum = 1;
                            while($row = $query->fetch()) {
                                echo '<tr>
                                <th scope="row">'.$rowNum.'</th>
                                <td>'.$row['nrp_hash'].'</td>
                                <td>'.$row['tahun'].'</td>
                                <td><form method="post" action="detail_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue">
                                <input type="hidden" name="nrp" value="' . $row['nrp_hash'] . '">
                                <input type="hidden" name="year" value="' . $row['tahun'] . '">
                                <button type="submit" name="detail" class="btn btn-dark">Detail</button>
                            </form></td>
                            </tr>';
                            $rowNum++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
    <!-- <script type="text/javascript">
        function redirectPage() {
            var selectedOption = document.getElementById("filtering").value;
            var redirectUrl;

            switch (selectedOption) {
                // case "Data List":
                //     redirectUrl = "data_list.php";
                //     break;
                // case "Distribusi Data":
                //     redirectUrl = "distribusi_data.php";
                //     break;
                // case "Jumlah":
                //     redirectUrl = "jumlah.php";
                //     break;
                // case "Rata-rata":
                //     redirectUrl = "rata_rata.php";
                //     break;
                case "Reporting":
                    redirectUrl = "reporting.php";
                    break;
                default:
                    // Default URL or error handling
                    break;
            }

            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        }
    </script> -->

        <!-- RATA-RATA CPL, BELOM BERDASARKAN TAHUN, ANGKATAN-->
    



        <!-- <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NRP</th>
                            <th scope="col">Tahun</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                        </tbody>
                    </table> 
                </div>
            </div>                       -->
        <?php
            // // Variabel yang ingin Anda kirim
            // $variabel = $tahun;

            // // URL tujuan yang menerima variabel
            // $tujuan = "reporting.php";

            // // Membuat URL dengan query string yang menyertakan variabel
            // $url = $tujuan . "?variabel=" . urlencode($variabel);

            // // Melakukan pengalihan ke halaman tujuan
            // header("location: ../cpl/reporting.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode");
            // exit; // Pastikan untuk keluar dari skrip saat melakukan redirect
        ?>

    </div>
</body>

</html>