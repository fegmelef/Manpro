<?php
include("../api/connect.php");


if (isset($_POST["nrp"])) {
    $nrp = $_POST['nrp'];
}

if (isset($_POST["year"])) {
    $year = $_POST['year'];
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
$sql_ipk = "SELECT ipk FROM ipk WHERE nrp_hash = '$nrp'";
$sql_ips = "SELECT * FROM ips WHERE nrp_hash = '$nrp'";
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
                <li class="breadcrumb-item"><a href="home_ipk.php">Home</a></li>
                <li class="breadcrumb-item active"><a href="data_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue">Data</a></li>
                <li class="breadcrumb-item active">Detail data</li>               
            </ul>
            </div>
        <!-- isi -->
        <div class="container">
            <div class="row g-2" style="margin-bottom:20px;">
                <!-- <div class="col-md-6">
                    <div class="p-3">Nama:</div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">Program Studi:</div>
                </div> -->
                <div class="col-md-6">
                    <div class="p-3">NRP: <?php echo $nrp; ?> </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">Angkatan: <?php echo $year; ?> </div>
                </div>
                <?php
                    $sql_ipk = $conn->prepare($sql_ipk);
                    $sql_ipk->execute();
                    $row_ipk = $sql_ipk->fetch();
                ?>
                <div class="col-md-6">
                    <div class="p-3">ipk: <?php echo $row_ipk['ipk']; ?> </div>
                </div>
            </div>

            <table border="1">
                <thead>
                    <tr>
                        <th>Mata Kuliah </th>
                        <th>Nilai Angka </th>
                        <th>Nilai Huruf </th>
                    </tr>
                </thead>
          

            <?php
                $nilai_A = 0;
                $nilai_B = 0;
                $nilai_BP = 0;
                $nilai_C = 0;
                $nilai_CP = 0;
                $nilai_D = 0;
                $nilai_E = 0;
                // $nrp='$nrp';
                
                $query = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, kelas_nilaicpmk.nilai, mk.mk, mhsw.nrp_hash, periode.tahun
                FROM kelas_cpmk
                JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                JOIN mk ON kelas.id_mk = mk.id_mk
                JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                JOIN periode ON kelas.id_periode = periode.id_periode  
                JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                WHERE mhsw.nrp_hash = ?
                GROUP BY mk.mk, kelas_nilaicpmk.nrp_hash
                ORDER BY `mhsw`.`nrp_hash` ASC";

                $query = $conn->prepare($query);
                $query->execute([$nrp]);

                
                $nilai_huruf='';
                $nilai_num=0;
                while ($row = $query->fetch()) {
                    if ($row['nilai CPL'] >= 86 AND $row['nilai CPL'] <= 100) {
                        $nilai_huruf = 'A';
                        $nilai_num = 4.0;
                        $nilai_A++;
                    }
                    else if ($row['nilai CPL'] >= 76 AND $row['nilai CPL'] <= 85) {
                        $nilai_huruf = 'B+';
                        $nilai_num = 3.5;
                        $nilai_BP++;
                    }
                    else if ($row['nilai CPL'] >= 69 AND $row['nilai CPL'] <= 75) {
                        $nilai_huruf = 'B';
                        $nilai_num = 3.0;
                        $nilai_B++;
                    }
                    else if ($row['nilai CPL'] >= 61 AND $row['nilai CPL'] <= 68) {
                        $nilai_huruf = 'C+';
                        $nilai_num = 2.5;
                        $nilai_CP++;
                    }
                    else if ($row['nilai CPL'] >= 56 AND $row['nilai CPL'] <= 60) {
                        $nilai_huruf = 'C';
                        $nilai_num = 2.0;
                        $nilai_C++;
                    }
                    else if ($row['nilai CPL'] >= 41 AND $row['nilai CPL'] <= 55) {
                        $nilai_huruf = 'D';
                        $nilai_num = 1.0;
                        $nilai_D++;
                    }
                    else if ($row['nilai CPL'] >= 0 AND $row['nilai CPL'] <= 40) {
                        $nilai_huruf = 'E';
                        $nilai_num = 0;
                        $nilai_E++;
                    }
            

                    // echo $row['mk']."<br>";
                    // echo $nilai_huruf." ".$nilai_num."<br>";
                    // echo "<tr>
                    //         <td>".$row['mk']."</td>
                    //         <td>".$nilai_huruf."</td>
                    //         <td>".$nilai_num."</td>
                    //     </tr>"."<break";

                    echo '<tr>
                            <td>'.$row['mk'].'</td>
                            <td>'.$nilai_num.'</td>
                            <td>'.$nilai_huruf.'</td>
                        </tr>';
                }

                $chart_data_nilai[] = array(
                    'nilaiA' =>$nilai_A,
                    'nilaiB' =>$nilai_B,
                    'nilaiBP' =>$nilai_BP,
                    'nilaiC' =>$nilai_C,
                    'nilaiCP' =>$nilai_CP,
                    'nilaiD' =>$nilai_D,
                    'nilaiE' =>$nilai_E
                );
            ?>
            </table>
            <br>
            <table border="1">
                <tr>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>IPS</th>
                </tr>
                <?php
                 $sql_ips = $conn->prepare($sql_ips);
                 $sql_ips->execute();
                 $semester;
                 while ($row_ips = $sql_ips->fetch()){
                    if ($row_ips['semester']==1){
                        $semester="ganjil";
                    }
                    else if ($row_ips['semester']==2){
                        $semester="genap";
                    }
                    echo '<tr>
                            <td>'.$row_ips['tahun'].'</td>
                            <td>'.$semester.'</td>
                            <td>'.$row_ips['ips'].'</td>
                        </tr>';
                 }
                ?>
            </table> 

            <canvas id="lineChart" width="400" height="200"></canvas>

            <!-- JavaScript to create the line chart -->
            <script>
                // Extracting data from PHP to JavaScript
                <?php
                $sql_ips->execute();
                $chartData = array();
                while ($row_ips = $sql_ips->fetch()){
                    $chartData[] = array(
                        'tahun' => $row_ips['tahun'],
                        'semester' => $semester,
                        'ips' => $row_ips['ips']
                    );
                }
                ?>
            var ctx = document.getElementById('lineChart').getContext('2d');
                var chartData = <?php echo json_encode($chartData); ?>;
                
                var data = {
                    labels: chartData.map(item => item.tahun + ' ' + item.semester),
                    datasets: [{
                        label: 'IPS',
                        data: chartData.map(item => item.ips),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                };

                var options = {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                };

                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: options
                });
            </script>
            
            
        </div>

<!-- Create a canvas element for the chart -->
<!-- <canvas id="barchart" width="200" height="200"></canvas> -->

<!-- JavaScript to create the line chart -->
<script>
    // Extracted data from PHP to JavaScript

    // var chart_data_nilai = <?php echo json_encode($chart_data_nilai); ?>;
    // var ctx = document.getElementById('barchart').getContext('2d');
    // var barChart = new Chart(ctx, {
    //         type: 'bar',
    //         data: {
    //             labels: Object.keys(chart_data_nilai),
    //             datasets: [{
    //                 label: 'Nilai',
    //                 data: Object.values(chart_data_nilai),
    //                 backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna latar belakang batang
    //                 borderColor: 'rgba(75, 192, 192, 1)', // Warna border batang
    //                 borderWidth: 1 // Lebar border batang
    //             }]
    //         },
    //         options: {
    //             scales: {
    //                 y: {
    //                     beginAtZero: true
    //                 }
    //             }
    //         }
    //     });
    // Create a bar chart
    
    // var ctx = document.getElementById('barchart').getContext('2d');
    // var $chart_data__nilai = <?php echo json_encode($chart_data_nilai); ?>;
    console.log(chart_data_nilai);
    // var xValues = ["A", "B+", "B", "C+", "C", "D", "E"];
    // var yValues = chart_data__nilai;

    // var myChart = new Chart(ctx, {
    //     type: "bar",
    //     data: {
    //         labels: xValues,
    //         datasets: [{
    //             backgroundColor: [
    //             'rgba(255, 99, 132, 0.2)',
    //             'rgba(255, 159, 64, 0.2)',
    //             'rgba(255, 205, 86, 0.2)',
    //             'rgba(75, 192, 192, 0.2)',
    //             'rgba(54, 162, 235, 0.2)',
    //             'rgba(153, 102, 255, 0.2)',
    //             'rgba(201, 203, 207, 0.2)'
    //         ],
    //         data: yValues
    // }]
    //     },
    //     options: {
    //         scales: {
    //         y: {
    //             beginAtZero: true
    //         }
    //     }
    //     }
    //     });

    // var data_nilai = {
    //     // labels: 'labels',
    //     datasets: [{
    //         label: 'Jumlah nilai',
    //         data: $chart_data__nilai,
    //         backgroundColor: [
    //             'rgba(255, 99, 132, 0.2)',
    //             'rgba(255, 159, 64, 0.2)',
    //             'rgba(255, 205, 86, 0.2)',
    //             'rgba(75, 192, 192, 0.2)',
    //             'rgba(54, 162, 235, 0.2)',
    //             'rgba(153, 102, 255, 0.2)',
    //             'rgba(201, 203, 207, 0.2)'
    //         ],
    //         borderColor: 'rgba(75, 192, 192, 1)',
    //         borderWidth: 1,
    //         // barPercentage: 0.5,
    //         // barThickness: 6,
    //         // maxBarThickness: 8,
    //         // minBarLength: 2
    //     }]
    // };

    // var options = {
    //     scales: {
    //         y: {
    //             beginAtZero: true
    //         }
    //     }
    // };

    // var bar_chart = new Chart(ctx, {
    //     type: 'bar',
    //     data: {},
    //     options: options
    // });

</script>
<div class="container">
            <div style="width: 100%;height: 100%">
		        <canvas id="barchart"></canvas>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
                <script>
                    var ctx = document.getElementById("barchart").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($chart_data_nilai); ?>,
                            datasets: [{
                                label: 'Nilai',
                                data: <?php echo json_encode($chart_data_nilai); ?>,
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

        
    </body>
</html>