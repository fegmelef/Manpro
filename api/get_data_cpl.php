<?php
$angkatan = $_POST['angkatan'];
$tahun = $_POST['tahun'];
$periode = $_POST['periode'];
header("location: ../cpl/data_cpl.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode");
?>