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
        <link rel="stylesheet" type="text/css" href="css.css">
    </head>
    <body>
        <!-- navbar -->
       
        <?php include "../navbar/navbar_after_login.php";?>
        <br>
        <br>
        <br>

        <!-- bread crumbs -->
        <div class="container">
            <div class="row">
            <ul id="breadcrumb" class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
            </ul>
            </div>
        </div>

        <!-- isi -->
        <div class="container">
            <div class="row">
                <div class="col-md-4">Periode</div>
                <div class="col-md-2">:</div>
                <div class="col-md-6">
                    <select name="periode" id="periode" class="form-control">
                        <option value="none">Pilih periode</option>
                        <option value="all">All</option>
                        <option value="gasal">Gasal</option>
                        <option value="genap">Genap</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">Tahun:</div>
                <div class="col-md-6">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="none">Pilih tahun</option>
                        <option value="all">All</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>                    
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">Angkatan:</div>
                <div class="col-md-6">
                    <select name="angkatan" id="angkatan" class="form-control">
                        <option value="none">Pilih angkatan</option>
                        <option value="all">All</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>                    
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 text-center"> <!-- Center the button within the column -->
                    <button type="button" class="btn"><a href="cpl/data_cpl.php" style="text-decoration: none;">Generate</a></button>
                </div>
            </div>
        </div>
    </body>
</html>