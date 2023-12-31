<?php
include 'api/connect.php';

if (isset($_POST["import"])) {
    $filename = $_FILES["file"]["tmp_name"];
    $filename2 = $_FILES["file"]["name"];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");

        $header = fgetcsv($file);

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Sanitize input data to prevent SQL injection
            $column = array_map('mysqli_real_escape_string', array_fill(0, count($column), $con), $column);

            switch ($filename2) {
                case 'periode.csv':
                    $sqlinsert = "INSERT INTO periode (id_periode, tahun, semester) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}')";
                    break;
    
                case 'mk.csv':
                    $sqlinsert = "INSERT INTO mk (id_mk, mk, sks, id_kurikulum) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}')";
                    break;

                case 'mhsw.csv':
                    $sqlinsert = "INSERT INTO mhsw (nrp_hash, nrp, nama, tahun) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}')";
                    break;

                case 'kurikulum.csv':
                    $sqlinsert = "INSERT INTO kurikulum (id_kurikulum, startDate, endDate, id_dept) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}')";
                    break;

                case 'kelas_nilaicpmk.csv':
                    $sqlinsert = "INSERT INTO kelas_nilaicpmk (id_nilaicpmk, nrp_hash, id_cpmk, nilai) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}')";
                    break;

                case 'kelas_cpmk.csv':
                    $sqlinsert = "INSERT INTO kelas_cpmk (id_cpmk, id_kelas, no_cpmk, id_ikcpl, persentase) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}', '{$column[4]}')";
                    break;

                case 'kelas.csv':
                    $sqlinsert = "INSERT INTO kelas (id_kelas, id_mk, id_kurikulum, id_periode, label) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}', '{$column[4]}')";
                    break;

                case 'ips.csv':
                    $sqlinsert = "INSERT INTO ips (nrp_hash, tahun, semester, sks, hasil, ips, angkatan) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}', '{$column[4]}', '{$column[5]}', '{$column[6]}')";
                    break;

                case 'ipk.csv':
                    $sqlinsert = "INSERT INTO ipk (nrp_hash, ipk, sum_ips, count_ips, tahun, angkatan, semester) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}', '{$column[3]}', '{$column[4]}', '{$column[5]}', '{$column[6]}')";
                    break;

                case 'ikcpl.csv':
                    $sqlinsert = "INSERT INTO ikcpl (id_ikcpl, ikcpl, id_cpl) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}')";
                    break;

                case 'departemen.csv':
                    $sqlinsert = "INSERT INTO departemen (id_dept, departemen) VALUES ('{$column[0]}', '{$column[1]}')";
                    break;

                case 'cpl.csv':
                    $sqlinsert = "INSERT INTO cpl (id_cpl, cpl, id_kurikulum) VALUES ('{$column[0]}', '{$column[1]}', '{$column[2]}')";
                    break;

                case 'admin.csv':
                    $sqlinsert = "INSERT INTO admin (username, password) VALUES ('{$column[0]}', '{$column[1]}')";
                    break;

                default:
                    // Handle unrecognized file types or do nothing
                    break;
            }

            if (isset($sqlinsert)) {
                $result = mysqli_query($con, $sqlinsert);
                if (!$result) {
                    // Handle query execution failure
                    echo "Error: " . mysqli_error($con);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Import</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="css.css">

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
    <style>
        body {
            overflow-x: hidden;
        }

        .row {
            display: inline;
            margin-right: 20px;
            margin-top: 10px;
            display: flex;
            flex:1;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .import-btn {
            margin-top: 20px;

        }
    </style>
</head>

<body>
    <!-- navbar -->
    <?php include "navbar/navbar_import.php";?>

    <div class="container">
        <div class="row">
            <form class="form-horizontal" action="" method="post" name="uploadcsv" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="custom-label" for="file">Choose File</label>
                            </div>
                            <div class="col-md-2">:
                            </div>

                            <div class="col-md-4">
                                <input type="file" name="file" accept=".csv" class="file-input">
                            </div>
                        </div>
                        
                        <div class="row">
                            <button type="submit" name="import" class="btn import-btn">Import</button>
                        </div>
                </form>
        </div>
            
    </div>
</body>
