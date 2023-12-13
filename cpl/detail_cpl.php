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
            <div class="col-md-9 col-xs-9">
                <ul id="breadcrumb" class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                    <!-- <li class="breadcrumb-item active"><a href="data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue">Data</a></li> -->
                    <li class="breadcrumb-item active">
                        <a href="data_cpl.php?angkatan1=<?php echo $angkatan1;?>&angkatan2=<?php echo $angkatan2;?>&tahun=<?php echo $tahun; ?>&tahun2=<?php echo $tahun2; ?>&periode=<?php echo $periode; ?>">
                            List Data
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Detail data</li>               
                </ul>
            </div>
        </div>
    <div id="content">
        <!-- isi -->
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-xs-9">
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="p-2">NRP: <?php echo $nrp; ?> </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="p-2">Angkatan: <?php echo $year; ?> </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-3">
                        <button type="submit" name="detail" class="btn btn-dark" onclick="downloadAsPDF()" id="download">Download as PDF</button>   
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
                <div class="row" style="margin-bottom: 15px">
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
            </div>
            
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <table class="table">
                        <tr>
                            <th class="bordered-header">No</th>
                            <th class="bordered-header">Kode MK</th>
                            <th class="bordered-header">Nama MK</th>
                            <th class="bordered-header">CPL 1</th>
                            <th class="bordered-header">CPL 2</th>
                            <th class="bordered-header">CPL 3</th>
                            <th class="bordered-header">CPL 4</th>
                            <th class="bordered-header">CPL 5</th>
                            <th class="bordered-header">CPL 6</th>
                            <th class="bordered-header">CPL 7</th>
                            <th class="bordered-header">CPL 8</th>
                            <th class="bordered-header">CPL 9</th>
                            <th class="bordered-header">CPL 10</th>
                        </tr>
                        <tbody>
                        <?php
                            $query = "SELECT ROUND(SUM((kelas_cpmk.persentase/100)*kelas_nilaicpmk.nilai),2) AS 'nilai CPL', kelas_cpmk.persentase, ikcpl.id_ikcpl, ikcpl.id_cpl, kelas_nilaicpmk.nilai, mk.id_mk, mk.mk, mhsw.nrp_hash, periode.tahun
                            FROM kelas_cpmk
                            JOIN kelas_nilaicpmk ON kelas_cpmk.id_cpmk = kelas_nilaicpmk.id_cpmk
                            JOIN ikcpl ON kelas_cpmk.id_ikcpl = ikcpl.id_ikcpl
                            JOIN kelas ON kelas_cpmk.id_kelas = kelas.id_kelas
                            JOIN mk ON kelas.id_mk = mk.id_mk
                            JOIN mhsw ON kelas_nilaicpmk.nrp_hash = mhsw.nrp_hash
                            JOIN periode ON kelas.id_periode = periode.id_periode  
                            JOIN cpl ON ikcpl.id_cpl = cpl.id_cpl
                            WHERE mhsw.nrp_hash = ? AND mk.id_mk = ?
                            GROUP BY mk.mk, ikcpl.id_ikcpl
                            ORDER BY ikcpl.id_cpl ASC";

                            $query2 = "SELECT mk.mk, mk.id_mk
                            FROM mk
                            JOIN kelas AS k ON k.id_mk=mk.id_mk 
                            JOIN kelas_cpmk AS kc ON kc.id_kelas=k.id_kelas
                            JOIN kelas_nilaicpmk AS kn ON kn.id_cpmk=kc.id_cpmk
                            WHERE kn.nrp_hash = ?
                            GROUP BY mk.mk";

                            #prepare
                            $query = $conn->prepare($query);
                            $query2 = $conn->prepare($query2);

                            #exec
                            $query2->execute([$nrp]);
                            
                            $rowNum = 1;
                            while ($row2 = $query2->fetch()) {
                                echo '<tr>
                                    <th class="bordered-cell">' . $rowNum . '</th><td class="bordered-cell">' . $row2['id_mk'] . '</td><td class="bordered-cell">' . $row2['mk'] . '</td>';

                                $query->execute([$nrp, $row2['id_mk']]);
                                $rows = $query->fetchAll();
                                $count = count($rows);

                                $start = 1;
                                foreach ($rows as $row) {
                                    for ($i = $start; $i <= 10; $i++) {
                                        if ($i == 10) {
                                            $id_cpl = 'TF-' . $i;
                                        } else {
                                            $id_cpl = 'TF-0' . $i;
                                        }
                                        if ($id_cpl == $row['id_cpl']) {
                                            echo '<td class="bordered-cell">' . $row['nilai CPL'] . '</td>';
                                            $start = $i + 1;
                                            if ($count > 1) {
                                                $count--;
                                                break;
                                            }
                                        } else {
                                            echo '<td class="bordered-cell">0</td>';
                                        }
                                    }
                                }
                                $rowNum++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
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
    </body>
</html>
