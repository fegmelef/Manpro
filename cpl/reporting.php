<?php
include ("../api/connect.php");
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

            
        <!-- isi -->
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                <select name="filtering" id="filtering" class="form-control1">
                    <option value="Data List">Data List</option>
                    <option value="Distribusi Data">Distribusi Data</option>
                    <option value="Jumlah">Jumlah</option>
                    <option value="Rata-rata">Rata-rata</option>                                  
                </select>
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
                            $query = $conn->prepare("SELECT kelas_nilaicpmk.*, ikcpl.id_ikcpl, mhsw.tahun, mhsw.nama
                            FROM kelas_nilaicpmk
                            JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk 
                            JOIN ikcpl ON ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl 
                            JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                            WHERE ikcpl.id_cpl = 'TF-01' 
                            AND kelas_nilaicpmk.nilai < (
                                SELECT AVG(nilai) 
                                FROM kelas_nilaicpmk
                                JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk 
                                JOIN ikcpl ON ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl 
                                WHERE ikcpl.id_cpl = 'TF-01' 
                                GROUP BY ikcpl.id_cpl
                            )");
                            $query->execute();
                            $rowNum = 1; 
                            while($row = $query->fetch()) {
                                echo '<tr>
                                <th scope="row">'.$rowNum.'</th>
                                <td>'.$row['nrp_hash'].'</td>
                                <td>'.$row['nama'].'</td>
                                <td>'.$row['nilai'].'</td>
                                <td>'.$row['id_ikcpl'].'</td>
                                <td>'.$row['tahun'].'</td>
                            </tr>';
                            $rowNum++; 
                            }

                            // try {
                            //     $query = $conn->prepare("SELECT kelas_nilaicpmk.* 
                            //                             FROM kelas_nilaicpmk 
                            //                             JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk 
                            //                             JOIN ikcpl ON ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl 
                            //                             WHERE ikcpl.id_cpl = 'TF-01' AND kelas_nilaicpmk.nilai < 
                            //                                     (SELECT AVG(nilai) FROM kelas_nilaicpmk 
                            //                                     JOIN kelas_cpmk ON kelas_nilaicpmk.id_cpmk = kelas_cpmk.id_cpmk 
                            //                                     JOIN ikcpl ON ikcpl.id_ikcpl = kelas_cpmk.id_ikcpl
                            //                                      WHERE ikcpl.id_cpl = 'TF-01'
                            //                                      GROUP BY ikcpl.id_cpl) AND kelas_nilaicpmk.nilai > 0");
                            //     $query->execute();
                            //     $rowNum = 1; 
                            //     while($row = $query->fetch()) {
                            //         echo '<tr>
                            //         <th scope="row">'.$rowNum.'</th>
                            //         <td>'.$row['nrp_hash'].'</td>
                            //         <td>'.$row['nilai'].'</td>
                            //     </tr>';
                            //     $rowNum++;
                            //     }
                            // } catch (PDOException $e) {
                            //     echo "Error: " . $e->getMessage();
                            // }

                            // try {
                            //     $query = $conn->prepare("SELECT * FROM kelas_nilaicpmk");
                            //     $query->execute();
                            //     $rowNum = 1; 
                            //     while($row = $query->fetch()) {
                            //         echo '<tr>
                            //         <th scope="row">'.$rowNum.'</th>
                            //         <td>'.$row['nrp_hash'].'</td>
                            //         <td>'.$row['nilai'].'</td>
                            //     </tr>';
                            //     $rowNum++;
                            //     }
                            // } catch (PDOException $e) {
                            //     echo "Error: " . $e->getMessage();
                            // }
                        ?>
                        
                        </tbody>
                    </table>
                </div>
            </div>               
        </div>
    </body>
</html>