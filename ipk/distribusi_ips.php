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
    <title>Distribusi IPS</title>
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
                <li class="breadcrumb-item"><a href="home_ipk.php">Home</a></li>
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
        if ($selectedValue == 'Pengaruh MK') {
            header("location: ../ipk/pengaruhMK.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Rata-rata IPK') {
            header("location: ../ipk/rata2_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
            exit;
        } else if ($selectedValue == 'Data List') {
            header("location: ../ipk/data_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
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
                        <option value="Pengaruh MK">Pengaruh MK</option>
                        <!-- <option value="Penuruan IPS">Jumlah</option> -->
                        <option value="Rata-rata IPK">Rata-rata</option>
                    </select>
                    <input type="submit" value="Kirim">
                </form>
                <button id="downloadCSV" onclick="downloadCSV()">Download CSV</button>
            </div>
        </div>
        <!-- RATA-RATA CPL, BELOM BERDASARKAN TAHUN, ANGKATAN-->
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <th scope="col">Nilai</th>
                        <th scope="col">Jumlah Mahasiswa</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                hasil,
                                COUNT(nrp_hash) AS jumlah_mahasiswa
                            FROM (
                                SELECT 
                                    nrp_hash, 
                                    tahun, 
                                    semester,
                                    angkatan,
                                    sks,
                                    ips,
                                    CASE 
                                        WHEN ips = 4.0 THEN 'A'
                                        WHEN ips BETWEEN 3.5 AND 3.99 THEN 'B+'
                                        WHEN ips BETWEEN 3.0 AND 3.49 THEN 'B'
                                        WHEN ips BETWEEN 2.5 AND 2.99 THEN 'C+'
                                        WHEN ips BETWEEN 2.0 AND 2.49 THEN 'C'
                                        WHEN ips BETWEEN 1.0 AND 1.99 THEN 'D'
                                        WHEN ips BETWEEN 0 AND 0.99 THEN 'E'
                                    END AS hasil
                                FROM ips
                            ) AS subquery
                            GROUP BY hasil";
                        

                        if ($periode !== "All") {
                            $sql .= " AND semester = :periode";
                        }

                        if ($angkatan !== "All") {
                            $sql .= " AND angkatan = :angkatan";
                        }

                        if ($tahun !== "All") {
                            $sql .= " AND tahun = :tahun";
                        }


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

                        if ($result) {
                            foreach ($result as $row) {
                                echo '<tr>
                                    <td>' . $row['hasil'] . '</td>
                                    <td>' . $row['jumlah_mahasiswa'] . '</td>
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
                link.setAttribute('download', 'distribusi_ips.csv');
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