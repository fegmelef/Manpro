<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Main Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css.css">
    </head>

    <body>
        <!-- <div class="row1">
            <nav class="navbar navbar-expand-lg navbar fixed-top">
                <div class="container-fluid">
                <div class="navbar-header">
                    <div class="column"> 
                    <a class="navbar-brand" href="#">
                    <img src="../assets/pcu_black.png" alt="Logo" width="100" height="100" class="logo-img">
                    </a>
                    </div>
                    <div class="column">
                    <a class="navbar-brand" href="#">
                    <img src="../assets/pcu_blue.png" alt="Logo" width="150" height="100" class="logo-img">
                    </a>
                    </div>
                </div>

                <ul id="nav" class="nav navbar-nav navbar-right">
                    <li><a href="../cpl/home_cpl.php">CPL</a></li>
                    <li><a href="../ipk/home_ipk.php">IPK</a></li>
                    <li><a href="../welcome_page.php">LOG OUT</a></li>
                </ul>
                </div>
            </nav>
        </div> -->
        
        <!-- DIBUAT KARENA KALAU PAKAI NAVBAR AFTER LOGIN BIASANYA GABISA GET SRC IMG NYA -->
        <nav class="navbar navbar-expand-lg navbar fixed-top">
            <a class="navbar-brand" href="#">
                <img src="assets/pcu_black.png" alt="Logo" width="100" height="100" class="logo-img">
            </a>
            <a class="navbar-brand" href="#">
                <img src="assets/pcu_blue.png" alt="Logo" width="150" height="100" class="logo-img">                    
            </a>
            <ul id="nav" class="nav navbar-nav navbar-right">
                <li><a href="import.php">IMPORT</a></li>
                <li><a href="cpl/home_cpl.php">CPL</a></li>
                <li><a href="ipk/home_ipk.php">IPK</a></li>
                <li><a href="welcome_page.php">LOG OUT</a></li>                
            </ul>
        </nav>
    </body>
</html>