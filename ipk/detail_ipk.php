<?php
include("../api/connect.php");
if (isset($_POST["nrp"])) {
    $nrp = $_POST['nrp'];
}

if (isset($_POST["year"])) {
    $year = $_POST['year'];
}
if (isset($_GET["angkatan1"]) && isset($_GET["angkatan2"])) {
    $angkatan1 = min($_GET['angkatan1'], $_GET['angkatan2']);
    $angkatan2 = max($_GET['angkatan1'], $_GET['angkatan2']);
}

if (isset($_GET["tahun"]) && isset($_GET["tahun2"])) {
    $tahun = min($_GET['tahun'], $_GET['tahun2']);
    $tahun2 = max($_GET['tahun'], $_GET['tahun2']);
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
        <title>Detail IPK</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" type="text/css" href="../css.css">
        <script type="text/javascript" src="../chartjs/Chart.js"></script>
    
        <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
        <style>
            body {
                overflow-x: hidden;
            }
        </style>
    </head>
    <body>
        <!-- navbar -->
        <?php include "../navbar/navbar_after_login.php";?>
        <!-- bread crumbs -->
        <div class="row">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_ipk.php">Home</a></li>
                <!-- <li class="breadcrumb-item active"><a href="data_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue">Data</a></li> -->
                <li class="breadcrumb-item active">
                    <a href="data_ipk.php?angkatan1=<?php echo $angkatan1;?>&angkatan2=<?php echo $angkatan2;?>&tahun=<?php echo $tahun; ?>&tahun2=<?php echo $tahun2; ?>&periode=<?php echo $periode; ?>">
                        Data
                    </a>
                </li>
                <li class="breadcrumb-item active">Detail IPK</li> 
            </ul>
        </div>

        <div id="content">
        <!-- isi -->
        <div class="container" name="content">
            <div class="row">
                <div class="col-md-9 col-xs-9">
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="p-3">NRP: <?php echo $nrp; ?> </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="p-3">Angkatan: <?php echo $year; ?> </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <?php
                            $sql_ipk = $conn->prepare($sql_ipk);
                            $sql_ipk->execute();
                            $row_ipk = $sql_ipk->fetch();
                            ?>
                            <div class="p-3">IPK: <?php echo $row_ipk['ipk']; ?> </div>
                        </div>
                    </div>   
                </div> 
                <div class="col-md-3 col-xs-3">
                    <button type="submit" name="detail" class="btn btn-dark" onclick="downloadAsPDF()" id="download">Download as PDF</button>   
                </div>            
            </div>

            <div class="container" name="content">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>


            <div style="width: 100%;height: 100%">
                <canvas id="ipkLineChart"></canvas>
            </div>

            <div class="container">
                <h3> Nilai Mata Kuliah </h3>
        <div class="row">
            <div class="col-md-12 col-xs-12">
            <table class="table" id="tabel_detail_ipk1">
                    <tr>
                        <th class="bordered-header">Mata Kuliah </th>
                        <th class="bordered-header">Nilai Angka </th>
                        <th class="bordered-header">Nilai Huruf </th>
                    </tr>
            <tbody>

            <?php
                $nilai_A = 0;
                $nilai_B = 0;
                $nilai_BP = 0;
                $nilai_C = 0;
                $nilai_CP = 0;
                $nilai_D = 0;
                $nilai_E = 0;
                // $nrp='$nrp';
                
                $query = "SELECT SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai) 
                AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, 
                kelas_nilaicpmk.nilai, mk.mk, mhsw.nrp_hash, periode.tahun
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

                    echo '<tr>
                            <td class="bordered-cell">'.$row['mk'].'</td>
                            <td class="bordered-cell">'.$nilai_num.'</td>
                            <td class="bordered-cell">'.$nilai_huruf.'</td>
                        </tr>';
                }
                // echo $nilai_A;
                // echo $nilai_B;
                // echo $nilai_BP;
                // echo $nilai_C;
                // echo $nilai_CP;
                $chart_data_nilai[] = array(
                    'nilaiA' =>$nilai_A,
                    'nilaiBP' =>$nilai_B,
                    'nilaiB' =>$nilai_BP,
                    'nilaiCP' =>$nilai_C,
                    'nilaiC' =>$nilai_CP,
                    'nilaiD' =>$nilai_D,
                    'nilaiE' =>$nilai_E
                );

            ?>
            </tbody>
            </table>
            </div>
            </div>
            <h3>IPS Tiap Periode</h3>
            <div class="row">
            <div class="col-md-12 col-xs-12">
            <table class="table" id="tabel_detail_ipk2">
                <tr>
                    <th class="bordered-header">Tahun</th>
                    <th class="bordered-header">Semester</th>
                    <th class="bordered-header">IPS</th>
                </tr>
                <tbody>
                <?php
                $labels = [];
                $values = [];

                $query1 = "SELECT nrp_hash, tahun, semester, SUM(ips.ips)/COUNT(ips.ips) AS 'Total_ips'		
                FROM ips 
                WHERE nrp_hash = ?
                GROUP BY tahun, semester";

                 $query1 = $conn->prepare($query1);
                 $query1->execute([$nrp]);

                 while ($row_ips = $query1->fetch()){
                    if ($row_ips['semester']==1){
                        $semester="ganjil";
                    }
                    else if ($row_ips['semester']==2){
                        $semester="genap";
                    }

                    $labels[] = $row_ips['tahun'] . ' - ' . $semester;
                    $values[] = $row_ips['Total_ips'];

                    echo '<tr>
                            <td class="bordered-cell">'.$row_ips['tahun'].'</td>
                            <td class="bordered-cell">'.$semester.'</td>
                            <td class="bordered-cell">'.$row_ips['Total_ips'].'</td>
                        </tr>';
                 }
                ?>
                </tbody>
            </table> 
                </div>
                </div>
                </div>

                <div style="width: 100%;height: 100%">
                    <canvas id="ipkLineChart"></canvas>
                    <!-- <canvas id="barChartNilai"></canvas> -->
                    <script>
                        var ctxline = document.getElementById("ipkLineChart").getContext('2d');
                        var ipkLineChart = new Chart(ctxline, {
                            type: 'line',
                            data: {
                                labels: <?php echo json_encode($labels); ?>,
                                datasets: [{
                                    label: 'ips',
                                    data: <?php echo json_encode($values); ?>,
                                    backgroundColor: 'rgba(75, 192, 192, 1)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 3
                                }]
                            },
                            options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                borderWidth: 10 // Atur lebar garis pada sumbu x di sini
                                            }
                                        
                                        }
                                    }
                                }
                            });

                            function downloadAsPDF(){
                                const isi = this.document.getElementById("content")
                                var opt = {
                                    margin:[5,5,5,5],
                                    filename: <?php echo json_encode($nrp)?>,
                                    html2canvas: {width:1250}
                                }
                                html2pdf().set({
                                    pagebreak: { mode: 'avoid-all', before: '#page2el' }
                                });
                                html2pdf().set(opt).from(isi).save();
                            }
                                                
                    </script>
            </div>
            
        </div>

    </body>
</html>