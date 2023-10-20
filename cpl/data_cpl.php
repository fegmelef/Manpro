<?php
include ("../api/connect.php");

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
                <div class="col-md-9">
                    <p class="semester">Semester: <?php echo $periode; ?><br>Angkatan: <?php echo $angkatan; ?></p>
                </div>
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
                            <th scope="col">Nama</th>
                            <th scope="col">NRP</th>
                            <th scope="col">Tahun</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $query = $conn->prepare("SELECT * FROM mhsw WHERE tahun = ?");
                            $twodigit = $angkatan % 100;
                            $query->execute([$twodigit]);
                            $rowNum = 1;
                            while($row = $query->fetch()) {
                                echo '<tr>
                                <th scope="row">'.$rowNum.'</th>
                                <td>'.$row['nama'].'</td>
                                <td>'.$row['nrp_hash'].'</td>
                                <td>'.$row['tahun'].'</td>
                            </tr>';
                            $rowNum++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
        </div>
    </body>
</html>