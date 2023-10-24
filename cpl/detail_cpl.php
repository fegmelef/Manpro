<?php
include("../api/connect.php");

if (isset($_POST["nrp"])) {
    $nrp = $_POST['nrp'];
}

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
        <title>Detail CPL</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="../css.css">
        <script type="text/javascript" src="../chartjs/Chart.js"></script>
    </head>
    <body>
        <!-- navbar -->
            <?php include "../navbar/navbar_after_login.php";?>
        <!-- bread crumbs -->
            <div class="row">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                <li class="breadcrumb-item active"><a href="data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue">Data</a></li>
                <li class="breadcrumb-item active">Detail data</li>               
            </ul>
            </div>
        <!-- isi -->
        <div class="container">
            <div class="row g-2" style="margin-bottom:20px;">
                <div class="col-md-6">
                    <div class="p-3">Nama:</div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">Program Studi:</div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">NRP: <?php echo $nrp; ?> </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">Periode/Tahun:</div>
                </div>
            </div>

            <?php
                $labels = [];
                $values = [];
                
                $query = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, kelas_nilaicpmk.nilai, mk.id_mk, mk.mk, CONCAT(mk.mk, ' ', cpl.id_cpl) AS 'nama CPL', mhsw.nrp_hash, periode.tahun
                    FROM kelas_cpmk
                    JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                    JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                    JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                    JOIN mk ON kelas.id_mk = mk.id_mk
                    JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                    JOIN periode ON kelas.id_periode = periode.id_periode  
                    JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                    WHERE mhsw.nrp_hash = ?
                    GROUP BY mk.mk, ikcpl.id_ikcpl
                    ORDER BY `mhsw`.`nrp_hash` ASC";

                $query = $conn->prepare($query);
                $query->execute([$nrp]);

                while($row = $query->fetch()) {
                    $labels[] = $row['nama CPL']; // Mengambil atribut nama sebagai label
                    $values[] = $row['nilai CPL']; // Mengambil atribut nilai untuk data grafik
                }
            ?>
            
            <div class="container">
            <div style="width: 100%;height: 100%">
		        <canvas id="myChart"></canvas>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
                <script>
                    var ctx = document.getElementById("myChart").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($labels); ?>,
                            datasets: [{
                                label: 'Nilai',
                                data: <?php echo json_encode($values); ?>,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }]
                            }
                        }
                    });
                </script>
	        </div>

            

            
        </div>

        <div class="col-md-12">
            <table borderWidth="1" style="width:100%;">
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
                    $query = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, kelas_nilaicpmk.nilai, mk.id_mk, mk.mk, mhsw.nrp_hash, periode.tahun
                    FROM kelas_cpmk
                    JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                    JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                    JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                    JOIN mk ON kelas.id_mk = mk.id_mk
                    JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                    JOIN periode ON kelas.id_periode = periode.id_periode  
                    JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                    WHERE mhsw.nrp_hash = ?
                    GROUP BY mk.mk, ikcpl.id_ikcpl
                    ORDER BY `mhsw`.`nrp_hash` ASC";

                    $query2 = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai CPL'
                    FROM kelas_cpmk
                    JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                    JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                    JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                    JOIN mk ON kelas.id_mk = mk.id_mk
                    JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                    JOIN periode ON kelas.id_periode = periode.id_periode  
                    JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                    WHERE mhsw.nrp_hash = ? AND cpl.id_cpl = ?
                    GROUP BY cpl.id_cpl, mk.mk, ikcpl.id_ikcpl
                    ORDER BY `mhsw`.`nrp_hash` ASC";

                    $query3 = "SELECT mk.mk
                    FROM mk
                    JOIN kelas AS k ON k.id_mk=mk.id_mk 
                    JOIN kelas_cpmk AS kc ON kc.id_kelas=k.id_kelas
                    JOIN kelas_nilaicpmk AS kn ON kn.id_cpmk=kc.id_cpmk
                    WHERE kn.nrp_hash = ?
                    GROUP BY mk.mk";

                    #prepare
                    $query = $conn->prepare($query);
                    $query2 = $conn->prepare($query2);
                    $query3 = $conn->prepare($query3);

                    #exec
                    $query->execute([$nrp]);
                    $query3->execute([$nrp]);

                    $jum_mk = 0; 
                    while($row2 = $query3->fetch()) {
                        $jum_mk = $jum_mk + 1;
                    }
                    
                    $rowNum = 1;
                    while($row = $query->fetch()) {
                        echo '<tr>
                        <th>'.$rowNum.'</th><td>'.$row['id_mk'].'</td>';
                        
                        for ($j=1;$j<=$jum_mk;$j++) {
                            echo '<td>'.$row['mk'].'</td>';
                            for ($i=1;$i<=10;$i++) {
                                if ($i==10) {
                                    $id_cpl = 'TF-'.$i;
                                }
                                else {
                                    $id_cpl = 'TF-0'.$i;
                                }
                                $query2->execute([$nrp, $id_cpl]);
                                if ($query2->rowCount()==0) {
                                    echo '<td>0</td>';
                                }
                                else {
                                    while($row1 = $query2->fetch()) {
                                        echo '<td>'.$row1['nilai CPL'].'</td>';
                                    }
                                }
                                // $nilai = 0;
                                // if ($query2->rowCount()>1) {
                                //     while($row = $query->fetch()) {
                                //         $nilai = $nilai + $row['nilai CPL'];
                                //     }
                                // }
                                }
                            }
                            $rowNum++;
                        }
                
                ?>
        </tbody>
            <!-- <tr>
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
            </tr> -->
    </table>
    </div>
        </div>
        
    </body>
</html>