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
        <style>
            @media only screen and (max-width: 750px) {
            .content-box {
               background-color:#e8907d; margin:auto; margin-top:100px; padding:20px; border-radius:5px
                }
            }
            @media only screen and (max-width: 630px) {
            .content-box {
                background-color:#e8907d; margin-top:100px; padding:20px; border-radius:5px;
                }   
            }
        </style>
    </head>

    <body>
        <div class="row">
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
                    <img src="../assets/pcu_blue.png" alt="Logo" width="100" height="100" class="logo-img">
                    </a>
                    </div>
                </div>

                <ul id="nav" class="nav navbar-nav navbar-right">
                    <li><a href="../cpl/home_cpl.php">CPL</a></li>
                    <li><a href="../ipk/home_ipk.php">IPK</a></li>
                    <li><a href="welcome_page.php">LOG OUT</a></li>
                </ul>
                </div>
            </nav>
        </div>
    </body>
</html>