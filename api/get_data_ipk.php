<?php
$angkatan = $_POST['angkatan'];
$tahun = $_POST['tahun'];
$periode = $_POST['periode'];
header("location: ../ipk/data_ipk.php?angkatan=$angkatan&&tahun=$tahun&&periode=$periode");
?>