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
        <div class="col-md-7">
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
    <div class="col-md-5">
        <form action="" method="post">
            <select name="filtering" id="filtering" class="form-control1" onchange="redirectPage()">
                <option value="selected value">
                    <?php echo $val; ?>
                </option>
                <option value="Data List">Data List</option>
                <option value="Rata-rata">Rata-rata</option>
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
        } else if ($selectedValue == 'Data List') {
            header("location: ../cpl/data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Rata-rata') {
            header("location: ../cpl/rata_rata.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Jumlah') {
            header("location: ../cpl/jumlah.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
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
                    <?php
                    $query2 = "SELECT mk.mk, mk.id_mk
            FROM mk
            JOIN kelas AS k ON k.id_mk=mk.id_mk 
            JOIN kelas_cpmk AS kc ON kc.id_kelas=k.id_kelas
            JOIN kelas_nilaicpmk AS kn ON kn.id_cpmk=kc.id_cpmk
            GROUP BY mk.id_mk";

                    $query2 = $conn->prepare($query2);

                    $query2->execute();

                    $rowNum = 1;
                    while ($row2 = $query2->fetch()) {
                        echo '<tr>
            <th>' . $rowNum . '</th><td>' . $row2['id_mk'] . '</td><td>' . $row2['mk'] . '</td>';

                        $query = "SELECT kelas_nilaicpmk.nilai, mk.id_mk, mk.mk, ikcpl.id_cpl, AVG(kelas_nilaicpmk.nilai) AS 'average nilai'
            FROM kelas_nilaicpmk
            JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk
            JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
            JOIN mk ON kelas.id_mk = mk.id_mk
            JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
            JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
            JOIN periode ON kelas.id_periode = periode.id_periode
            WHERE mk.id_mk = ?";

                        if ($periode !== "All") {
                            $query .= " AND periode.semester = :periode";
                        }

                        if ($angkatan !== "All") {
                            $query .= " AND mhsw.tahun = :angkatan";
                        }

                        if ($tahun !== "All") {
                            $query .= " AND periode.tahun = :tahun";
                        }

                        $query .= " GROUP BY mk.id_mk";
                        // echo ($query);

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

                        $query->execute([$row2['id_mk']]);

                        $rows = $query->fetchAll();

                        for ($i = 1; $i <= 10; $i++) {
                            $found = false;

                            foreach ($rows as $row) {
                                if ($i == 10) {
                                    $id_cpl = 'TF-' . $i;
                                } else {
                                    $id_cpl = 'TF-0' . $i;
                                }

                                if ($id_cpl == $row['id_cpl']) {
                                    echo '<td>' . number_format($row['average nilai'], 2) . '</td>';
                                    $found = true;
                                    break;
                                }
                            }

                            if (!$found) {
                                echo '<td>0</td>';
                            }
                        }

                        $rowNum++;
                    }
                    ?>


                </tbody>
            </table>
        </div>
    </div>
</body>

</html>