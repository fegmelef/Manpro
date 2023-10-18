<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Welcome Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css.css">
    </head>
    <body>
        <!-- navbar -->
        <?php include "navbar/navbar_after_login.php";?>
        <!-- bread crumbs -->
        <!-- isi -->
        <div class="container overflow-hidden text-center">
            <div class="row gy-5">
                <div class="col-6">Periode:</div>
                <div class="col-6">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Select an option
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">All</a></li>
                            <li><a class="dropdown-item" href="#">Gasal</a></li>
                            <li><a class="dropdown-item" href="#">Genap</a></li>
                        </ul>
                    </div>
                </div>
            <!-- </div> -->
            <!-- <div class="row gy-5"> -->
                <div class="col-6">Tahun:</div>
                <div class="col-6"></div>
            <!-- </div> -->
            <!-- <div class="row gy-5"> -->
                <div class="col-6">Angkatan:</div>
                <div class="col-6"></div>
            <!-- </div> -->
</div>
        </div>
    </body>
</html>