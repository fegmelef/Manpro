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

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
    <style>
        body {
            overflow-x: hidden;
        }
        th {
        cursor: pointer;
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
    </div>

    <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Mengambil nilai dropdown yang dipilih
                    $selectedValue = $_POST['filtering'];
                    if ($selectedValue == 'Distribusi') {
                        header("location: ../ipk/distribusi_ips.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Pengaruh MK') {
                        header("location: ../ipk/pengaruhMK.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                        exit;
                    } 
                    else if ($selectedValue == 'Rata-rata IPK') {
                        header("location: ../ipk/rata2_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
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
                       
                        <option value="Data List">Data List</option>
                        <option value="Distribusi">Distribusi</option>
                        <option value="Pengaruh MK">Pengaruh MK</option>
                        <option value="Rata-rata IPK">Rata-rata</option>
                    </select>
                    <input type="submit" value="Kirim">
                </form>
                <button id="downloadCSV" onclick="downloadCSV()">Download CSV</button>
            </div>
        </div>
        <div class="row">
                <div class="col-md-12">
                    <table class="table" id="tabel_ipk">
                        <thead>
                            <tr>
                                <th scope="col" onclick="sortTable(0)">No</th>
                                <!-- <th scope="col">Nama</th> -->
                                <th scope="col" onclick="sortTable(1)">NRP</th>
                                <th scope="col" onclick="sortTable(2)">Tahun</th>
                                <th scope="col"> </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // $query = $conn->prepare("SELECT * FROM mhsw WHERE mhsw.tahun = $angkatan ");
                            $sql = "SELECT * FROM mhsw";

                            if ($angkatan !== 'All'){
                                $sql .= " WHERE mhsw.tahun = '$angkatan'";
                            }

                            $query = $conn->prepare($sql);
                            $query->execute();
                            $rowNum = 1;
                            while($row = $query->fetch()) { ?>
                                <tr>
                                <td scope="row"><?php echo $rowNum; ?></td>
                                <td><?php echo $row['nrp_hash'];?></td>
                                <td><?php echo $row['tahun'];?></td>
                                <td><form method="post" action="detail_ipk.php?angkatan=<?php echo $angkatan; ?>&tahun=<?php echo $tahun; ?>&periode=<?php echo $periode; ?>">
                                <input type="hidden" name="nrp" value="<?php echo $row['nrp_hash'];?>">
                                <input type="hidden" name="year" value="<?php echo $row['tahun'];?>">
                                <button type="submit" name="detail" class="btn btn-dark">Detail</button>
                                </form></td>
                            </tr>
                            <?php
                            $rowNum++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    <script>
        var sort = "ascending";
        function sortTable(n) {

            var table, rows, switching, i, x, y, shouldSwap;
            table = document.getElementById("tabel_ipk");
            switching = true;
            rows = table.getElementsByTagName("TR");
            console.log(sort);
            for (i = 1; i < (rows.length - 1); i++) {
                if (n==1){
                    max = rows[1].getElementsByTagName("TD")[1].textContent.toString();
                    min = "";
                }else{
                    max = 0;
                    min = Infinity;
                }

                for (j = i; j < (rows.length); j++) {
                    shouldSwap = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[j].getElementsByTagName("TD")[n];

                    if (n==0 || n==2){
                        xValue = parseInt(x.textContent.toString());
                        yValue = parseInt(y.textContent.toString());
                    }else{
                        xValue = x.textContent.toLowerCase();
                        yValue = y.textContent.toLowerCase();
                    }
                    
                    if(sort == "ascending"){
                        if (max < yValue) {
                            max = yValue;
                            index = j;
                        }
                    }else if (sort == "descending"){
                        if (min > yValue) {
                            min = yValue;
                            index = j;
                        }
                    }
                    
                }
                if (sort == "ascending") {
                    console.log(max);  
                    if (xValue <= max){
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                }else{
                    console.log(min);
                    if (xValue >= min){
                        rows[i].parentNode.insertBefore(rows[index], rows[i]);
                    }
                }
            }
            if(sort == "ascending"){
                sort = "descending";
            }else{
                sort = "ascending";
            }
            console.log(rows)
        }

    //DOWNLOAD CSV
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
                link.setAttribute('download', 'data_ipk.csv');
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

