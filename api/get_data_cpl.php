<?php
$angkatan1 = $_POST['angkatan1'];
$angkatan2 = $_POST['angkatan2'];
$tahun = $_POST['tahun'];
$tahun2 = $_POST['tahun2'];
$periode = $_POST['periode'];

header("location: ../cpl/data_cpl.php?angkatan1=$angkatan1&angkatan2=$angkatan2&tahun=$tahun&tahun2=$tahun2&periode=$periode");
?>