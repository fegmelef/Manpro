<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Welcome Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="../css.css">
    </head>
    <body>
        <!-- navbar -->
            <?php include "../navbar/navbar_after_login.php";?>
        <!-- bread crumbs -->
            <div class="row">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item"><a href="home_cpl.php">Home</a></li>
                <li class="breadcrumb-item active"><a href="data_cpl.php">Data</a></li>
                <li class="breadcrumb-item active">Detail data</li>               
            </ul>
            </div>
        <!-- isi -->
        <div class="container">
            <div class="row g-2" style="">
                <div class="col-md-6">
                    <div class="p-3">Nama:</div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">Program Studi:</div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">NRP:</div>
                </div>
                <div class="col-md-6">
                    <div class="p-3">Periode/Tahun:</div>
                </div>
            </div>
        </div>
    </body>
</html>