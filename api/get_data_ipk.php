<?php
$angkatan1 = $_POST['angkatan1'];
$angkatan2 = $_POST['angkatan2'];
$tahun = $_POST['tahun'];
$tahun2 = $_POST['tahun2'];
$periode = $_POST['periode'];

// Redirect ke halaman data_ipk.php dengan mengirimkan angkatan1, angkatan2, tahun, dan periode
header("location: ../ipk/data_ipk.php?angkatan1=$angkatan1&&angkatan2=$angkatan2&&tahun=$tahun&&tahun2=$tahun2&&periode=$periode");
?>