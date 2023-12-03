<?php
include("../api/connect.php");

if (isset($_GET["angkatan1"]) && isset($_GET["angkatan2"])) {
    $angkatan1 = min($_GET['angkatan1'], $_GET['angkatan2']);
    $angkatan2 = max($_GET['angkatan1'], $_GET['angkatan2']);
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
    <title>Data CPL</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <link rel="stylesheet" type="text/css" href="../css.css">

    <!-- lock screen, spy tdk bisa di swipe kanan kiri -->
    <style>
        body {
            overflow-x: hidden;
        }

        th {
            cursor: pointer;
        }

        ;
    </style>
</head>

<body>
    <!-- navbar -->
    <?php include "../navbar/navbar_after_login.php"; ?>

    <!-- bread crumbs -->
    <div class="row">
        <div class="col-md-9  col-xs-9">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                <li class="breadcrumb-item active">Rata-rata Nilai</li>
            </ul>
        </div>

        <!-- HARUS INI DULU SOALNYA NANTI VARIABEL NYA MAU DI POST KE HALAMAN LAIN -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mengambil nilai dropdown yang dipilih
            $selectedValue = $_POST['filtering'];

            // Membuat pernyataan if berdasarkan nilai dropdown
            if ($selectedValue == 'Daftar Mahasiswa Dibawah Rata-rata Nilai') {
                header("location: ../cpl/reporting.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'List Data') {
                header("location: ../cpl/data_cpl.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'Jumlah Mahasiswa Mengulang MK') {
                header("location: ../cpl/jumlah.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            } else if ($selectedValue == 'Distribusi Nilai') {
                header("location: ../cpl/distribusi.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&periode=$periode&&val=$selectedValue");
                exit;
            }

        }
        ?>

        <!-- isi -->
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-xs-7">
                    <p class="semester">Semester <span>
                            <?php echo $periode; ?>
                        </span> || Angkatan <span>
                            <?php echo $angkatan1; ?>
                        </span>-<span>
                            <?php echo $angkatan2; ?>
                        </span> || Tahun <span>
                            <?php echo $tahun; ?>
                        </span></p>
                </div>

                <div class="col-md-4 col-xs-4">
                    <form action="" method="post">
                        <div class="col-md-10 col-xs-10">
                            <select name="filtering" id="filtering" class="form-control" onchange="redirectPage()">
                                <option value="selected value">
                                    <?php echo $val; ?>
                                </option>
                                <option value="List Data">List Data</option>
                                <option value="Distribusi Nilai">Distribusi Nilai</option>
                                <option value="Jumlah Mahasiswa Mengulang MK">Jumlah Mahasiswa Mengulang MK</option>
                                <!-- <option value="Rata-rata Nilai">Rata-rata Nilai</option> -->
                                <option value="Daftar Mahasiswa Dibawah Rata-rata Nilai">Daftar Mahasiswa Dibawah
                                    Rata-rata Nilai</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <input type="submit" value="Kirim" class="btn btn-primary">
                        </div>
                    </form>
                </div>

                <div class="col-md-1 col-xs-1">
                    <div class="col-md-1 col-xs-1">
                        <svg id="downloadCSV" onclick="downloadCSV()" xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 512 512" style="cursor: pointer;">
                            <path
                                d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                    </div>
                </div>

                <canvas id="gradeChart" width="800" height="400"></canvas>

                <!-- RATA-RATA CPL, BELOM BERDASARKAN TAHUN, ANGKATAN-->
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <table class="table" id="rata2_cpl">
                            <tr>
                                <th class="bordered-header" scope="col" onclick="sorTable(0)">CPL</th>
                                <th class="bordered-header" scope="col" onclick="sortTable(1)">Tahun</th>
                                <th class="bordered-header" scope="col" onclick="sortTable(2)">Angkatan</th>
                                <th class="bordered-header" scope="col" onclick="sortTable(3)">Semester</th>
                                <th class="bordered-header" scope="col" onclick="sortTable(4)">Rata-rata Nilai</th>
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

                                if ($angkatan1 !== "All") {
                                    $sql .= " AND mhsw.tahun between :angkatan1 and :angkatan2";
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

                                if ($angkatan1 !== "All") {
                                    $query->bindParam(':angkatan1', $angkatan1, PDO::PARAM_STR);
                                }
                                if ($angkatan2 !== "All") {
                                    $query->bindParam(':angkatan2', $angkatan2, PDO::PARAM_STR);
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
                                    <td class="bordered-cell">' . $row['id_cpl'] . '</td>
                                    <td class="bordered-cell">' . $row['tahun'] . '</td>
                                    <td class="bordered-cell">' . $row['angkatan'] . '</td>
                                    <td class="bordered-cell">' . $row['semester'] . '</td>
                                    <td class="bordered-cell">' . $row['rata_nilai'] . '</td>
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
            <script>
                // Your PHP data as JavaScript array
                var data = <?php echo json_encode($result); ?>;

                // Object to store sum and count for each id_cpl
                var idCplData = {};

                // Loop through the data array
                data.forEach(function (row) {
                    var idCpl = row.id_cpl;
                    var rataNilai = parseFloat(row.rata_nilai);

                    if (!idCplData[idCpl]) {
                        // If id_cpl is not seen before, initialize the data
                        idCplData[idCpl] = {
                            sum: rataNilai,
                            count: 1
                        };
                    } else {
                        // If id_cpl is seen before, update the sum and count
                        idCplData[idCpl].sum += rataNilai;
                        idCplData[idCpl].count += 1;
                    }
                });

                // Calculate average for each id_cpl
                Object.keys(idCplData).forEach(function (idCpl) {
                    var average = idCplData[idCpl].sum / idCplData[idCpl].count;
                    console.log('id_cpl:', idCpl);
                    console.log('average_rata_nilai:', average);
                    console.log('---');
                });


                // Preprocess data to calculate average values for each id_cpl and angkatan
                var idCplAngkatanData = {};

                data.forEach(function (row) {
                    var idCpl = row.id_cpl; // Assuming id_cpl is the identifier for TF
                    var angkatan = row.angkatan;
                    var value = parseFloat(row.rata_nilai); // Assuming rata_nilai is the numeric value

                    if (!idCplAngkatanData[idCpl]) {
                        idCplAngkatanData[idCpl] = {};
                    }

                    if (!idCplAngkatanData[idCpl][angkatan]) {
                        idCplAngkatanData[idCpl][angkatan] = { sum: value, count: 1 };
                    } else {
                        idCplAngkatanData[idCpl][angkatan].sum += value;
                        idCplAngkatanData[idCpl][angkatan].count += 1;
                    }
                });

                // Calculate average values for each id_cpl and angkatan
                var averageData = [];

                Object.keys(idCplAngkatanData).forEach(function (idCpl) {
                    var angkatanValues = idCplAngkatanData[idCpl];

                    Object.keys(angkatanValues).forEach(function (angkatan) {
                        var averageValue = angkatanValues[angkatan].sum / angkatanValues[angkatan].count;

                        // Add an entry for each angkatan
                        averageData.push({ id_cpl: idCpl, angkatan: angkatan, average: averageValue });
                    });
                });

                console.log(averageData);

                // Create the chart using the calculated average values
                var ctx = document.getElementById('gradeChart').getContext('2d');

                // Extract unique IDs from the averageData, sort them in ascending order, and then reverse the order
                var uniqueIds = Array.from(new Set(averageData.map(row => row.id_cpl)))
                    .sort((a, b) => {
                        // Extract numerical parts from the id_cpl (assuming the format is tf01, tf02, etc.)
                        const numA = parseInt(a.slice(2));
                        const numB = parseInt(b.slice(2));
                        return numA - numB;
                    })
                    .reverse();

                // Extract unique Angkatan values
                var uniqueAngkatan = Array.from(new Set(averageData.map(row => row.angkatan)));

                var predefinedColors = ['#FF5733', '#33FF57', '#5733FF', '#FF33A1', '#33A1FF'];

                // Function to get a consistent color based on an index
                function getConsistentColor(index) {
                    // Use modulo to ensure the index wraps around if it exceeds the number of predefined colors
                    var colorIndex = index % predefinedColors.length;
                    return predefinedColors[colorIndex];
                }

                // Create datasets array
                var datasets = uniqueAngkatan.map(angkatan => {
                    return {
                        label: 'Angkatan ' + angkatan,
                        data: uniqueIds.map(idCpl => {
                            // Find the corresponding row for the current ID_CPL and Angkatan
                            var rowData = averageData.find(row => row.id_cpl === idCpl && row.angkatan === angkatan);
                            // Return the average value if the row exists, otherwise, return null
                            return rowData ? rowData.average : null;
                        }),
                        borderColor: getConsistentColor(),
                        borderWidth: 2,
                        fill: false
                    };
                });

                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: uniqueIds,  // Use uniqueIds for x-axis
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 5,  // Atur langkah antar angka pada sumbu Y
                                    // maxTicksLimit: 6  // Atur jumlah maksimum label pada sumbu Y
                                }
                            }
                        }
                    }
                });

                // Function to generate a random color
                // function getRandomColor() {
                //     var letters = '0123456789ABCDEF';
                //     var color = '#';
                //     for (var i = 0; i < 6; i++) {
                //         color += letters[Math.floor(Math.random() * 16)];
                //     }
                //     return color;
                // }


                // // Function to generate a random color
                // function getRandomColor() {
                //     var letters = '0123456789ABCDEF';
                //     var color = '#';
                //     for (var i = 0; i < 6; i++) {
                //         color += letters[Math.floor(Math.random() * 16)];
                //     }
                //     return color;
                // }



                // // Create the chart using the calculated average values
                // var ctx = document.getElementById('gradeChart').getContext('2d');

                // // Extract unique IDs from the averageData
                // var uniqueIds = Array.from(new Set(averageData.map(row => row.id_cpl)));

                // // Create datasets array
                // var datasets = uniqueIds.map(idCpl => {
                //     return {
                //         label: 'ID_CPL ' + idCpl,
                //         data: averageData
                //             .filter(row => row.id_cpl === idCpl)
                //             .map(row => row.average),
                //         borderColor: getRandomColor(),
                //         borderWidth: 2,
                //         fill: false
                //     };
                // });

                // var myChart = new Chart(ctx, {
                //     type: 'line',
                //     data: {
                //         labels: Array.from(new Set(averageData.map(row => row.angkatan))),
                //         datasets: datasets
                //     },
                //     options: {
                //         scales: {
                //             y: {
                //                 beginAtZero: true
                //             }
                //         }
                //     }
                // });
                // // Create the chart using the calculated average values
                // // var ctx = document.getElementById('gradeChart').getContext('2d');
                // // var myChart = new Chart(ctx, {
                // //     type: 'line',
                // //     data: {
                // //         labels: Array.from(new Set(averageData.map(row => row.angkatan))),
                // //         datasets: averageData.reduce(function (acc, row) {
                // //             if (!acc[row.id_cpl]) {
                // //                 acc[row.id_cpl] = {
                // //                     label: 'ID_CPL ' + row.id_cpl,
                // //                     data: [],
                // //                     borderColor: getRandomColor(),
                // //                     borderWidth: 2,
                // //                     fill: false
                // //                 };
                // //             }

                // //             acc[row.id_cpl].data.push(row.average);
                // //             return acc;
                // //         }, {})
                // //     },
                // //     options: {
                // //         scales: {
                // //             y: {
                // //                 beginAtZero: true
                // //             }
                // //         }
                // //     }
                // // });

                // // Function to generate a random color
                // function getRandomColor() {
                //     var letters = '0123456789ABCDEF';
                //     var color = '#';
                //     for (var i = 0; i < 6; i++) {
                //         color += letters[Math.floor(Math.random() * 16)];
                //     }
                //     return color;
                // }

            </script>

            <script>
                var sort = "ascending";
                function sortTable(n) {

                    var table, rows, switching, i, x, y, shouldSwap;
                    table = document.getElementById("rata2_cpl");
                    switching = true;
                    rows = table.getElementsByTagName("TR");
                    // console.log(sort);
                    for (i = 1; i < (rows.length - 1); i++) {
                        if (n == 0 || n == 1) {
                            max = rows[1].getElementsByTagName("TD")[1].textContent.toString();
                            min = "";
                        } else {
                            max = 0;
                            min = Infinity;
                        }

                        for (j = i; j < (rows.length); j++) {
                            shouldSwap = false;
                            x = rows[i].getElementsByTagName("TD")[n];
                            y = rows[j].getElementsByTagName("TD")[n];

                            if (n == 0 || n == 2) {
                                xValue = parseInt(x.textContent.toString());
                                yValue = parseInt(y.textContent.toString());
                            } else {
                                xValue = x.textContent.toLowerCase();
                                yValue = y.textContent.toLowerCase();
                            }

                            if (sort == "ascending") {
                                if (max < yValue) {
                                    max = yValue;
                                    index = j;
                                }
                            } else if (sort == "descending") {
                                if (min > yValue) {
                                    min = yValue;
                                    index = j;
                                }
                            }

                        }
                        if (sort == "ascending") {
                            // console.log(max);  
                            if (xValue <= max) {
                                rows[i].parentNode.insertBefore(rows[index], rows[i]);
                            }
                        } else {
                            // console.log(min);
                            if (xValue >= min) {
                                rows[i].parentNode.insertBefore(rows[index], rows[i]);
                            }
                        }
                    }
                    if (sort == "ascending") {
                        sort = "descending";
                    } else {
                        sort = "ascending";
                    }
                    // console.log(rows)
                }
                function downloadCSV() {
                    var table = document.querySelector('table'); // Get the table element
                    var rows = Array.from(table.querySelectorAll('tr')); // Get all rows in the table

                    // Create a CSV content string
                    var csvContent = rows.map(function (row) {
                        var rowData = Array.from(row.querySelectorAll('th, td'))
                            .map(function (cell) {
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
                            link.setAttribute('download', 'Rata_Rata.csv');
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