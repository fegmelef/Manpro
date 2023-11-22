<?php
include "../api/connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home IPK</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="../css.css">

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
            <li class="breadcrumb-item active">Home</li>
        </ul>
    </div>

    <!-- isi -->
    <form action="../api/get_data_ipk.php" method="POST">
        <div class="container">
            <div class="row">
                <div class="col-md-4">Periode</div>
                <div class="col-md-2">:</div>
                <div class="col-md-6">
                    <select name="periode" id="periode" class="form-control" required>
                        <option value="All">All</option>
                        <option value="1">Gasal</option>
                        <option value="2">Genap</option>


                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">Tahun</div>
                <div class="col-md-2">:</div>
                <div class="col-md-6">
                    <select name="tahun" id="tahun" class="form-control" required>
                        <!-- <option value="none">Pilih tahun</option> -->
                        <option value="All">All</option>
                        <!-- <option value="21">2021</option> -->
                        <?php
                        $query = mysqli_query($con, "SELECT DISTINCT tahun FROM `periode`");
                        while ($row = mysqli_fetch_array($query)) {
                            echo "<option value='" . $row['tahun'] . "'>" . $row['tahun'] . "</option>";
                        }
                        
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">Angkatan</div>
                <div class="col-md-2">:</div>
                <div class="col-md-6">
                    <select name="angkatan" id="angkatan" class="form-control" required>
                        <!-- <option disabled>Pilih angkatan</option> -->
                        <option value="All">All</option>
                        <?php
                        $query = mysqli_query($con, "SELECT DISTINCT tahun FROM `mhsw` ORDER BY tahun");
                        while ($row = mysqli_fetch_array($query)) {
                            echo "<option value='" . $row['tahun'] . "'>" . $row['tahun'] . "</option>";
                        }
                        ?>
                        <!-- <option value="19">2019</option>
                        <option value="20">2020</option>
                        <option value="21">2021</option>
                        <option value="22">2022</option>
                        <option value="23">2023</option> -->
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <!-- Center the button within the column -->
                    <button type="submit" name="generate_ipk" class="btn">Generate</button>
                </div>
            </div>
        </div>
    </form>
</body>

</html>