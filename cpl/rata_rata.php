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
</div>


    <!-- RATA-RATA CPL, BELOM BERDASARKAN TAHUN, ANGKATAN-->
<div class="row">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <th scope="col">CPL</th>
                        <th scope="col">Tahun</th>
                        <th scope="col">Angkatan</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Rata-rata Nilai</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT ikcpl.id_cpl, periode.tahun, mhsw.tahun AS angkatan, periode.semester, AVG(nilai) AS rata_nilai
                        FROM periode, kelas, mhsw, kelas_nilaicpmk, kelas_cpmk, ikcpl
                        WHERE mhsw.nrp_hash = kelas_nilaicpmk.nrp_hash
                        AND periode.id_periode = kelas.id_periode
                        AND kelas.id_kelas = kelas_cpmk.id_kelas
                        AND kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk
                        AND ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl";
                    
                    if ($periode !== "All") {
                        $sql .= " AND periode.semester = :periode";
                    }
                    
                    if ($angkatan !== "All") {
                        $sql .= " AND mhsw.tahun = :angkatan";
                    }
                    
                    if ($tahun !== "All") {
                        $sql .= " AND periode.tahun = :tahun";
                    }
                    
                    $sql .= " GROUP BY ikcpl.id_cpl, periode.tahun, mhsw.tahun, periode.semester";
                    $sql .= " ORDER BY mhsw.tahun ASC, ikcpl.id_cpl ASC";
                    
                    $query = $conn->prepare($sql);
                    
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
                    $result = $query->fetchAll();
                    
                    
                    $query->execute();
                    $result = $query->fetchAll();

                        if ($result) {
                            foreach ($result as $row) {
                                echo '<tr>
                    <td>' . $row['id_cpl'] . '</td>
                    <td>' . $row['tahun'] . '</td>
                    <td>' . $row['angkatan'] . '</td>
                    <td>' . $row['semester'] . '</td>
                    <td>' . $row['rata_nilai'] . '</td>
                </tr>';
                            }
                        } else {
                            echo "Tidak ada data yang ditemukan.";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

</body>
</html>
