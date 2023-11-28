<?php
include 'api/connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username=$_POST['username'];
    $password = $_POST['password'];
    
    $query_sql = "SELECT * FROM admin
            WHERE username = ? AND password = ? ";

    $stmt = $conn->prepare($query_sql);
    $stmt->execute([$username,$password]);
    $user = $stmt->fetch();

    if($stmt->rowCount() > 0){
        $_SESSION['admin_id'] = $user['admin_id'];
        header("Location: ../index.php "); #masuk
    }
    else {
        header('Location: login.php?login=failed');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>

    <style>
        .login-form {
            /* background-color: white; */
            max-width: 750px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: none;
        }

        .text {
            color: white;
            text-decoration: none;
        }
        .text:hover {
            color: white; 
            text-decoration: none; 
        }
        .mb-3{
            width: 600px;   
            margin: 0 auto;     
        }
        /* .btn {
            width: 100px;
            margin: 0 auto; 
        } */
        .btn-midnightblue {
            background-color: midnightblue;
            color: white; /* Set the text color to ensure visibility */
            width: 100px;
            margin: 0 auto;
        }

        .btn-midnightblue:hover {
            background-color: darkblue; /* Change the color on hover if needed */
        }
        .row {
            display: inline;
            margin-right: 20px;
            margin-top: 10px;
            display: flex;
            flex:1;
            justify-content: center;
            align-items: center;
        }
    </style>

</head>

<body>
    <?php include "navbar/navbar_login.php";?>

    <div class="container">
        <div class="login-form">
            <?=isset($msg) ? '<div class="alert alert-danger">'.$msg.'</div>' : ''?>
            <form method="POST" action="login.php">
                <div class="row">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label"><b>Username</b></label>
                        <input type="text" class="form-control" placeholder="Enter Username" id="enterusername"
                            name="username">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><b>Password</b></label>
                        <input type="password" class="form-control" placeholder="Enter Password" id="enteruserpassword" name="password">
                    </div>
                </div>
                <div class="row">
                    <div class="d-grid gap-2">
                        <!-- <button class="btn" name="login"><a href="cpl/home_cpl.php">Login</a></button> -->
                        <!-- <button type="button" class="btn btn-secondary btn-md"><a class="text" href="cpl/home_cpl.php">Login</a></button> -->
                        <!-- <button type="button" class="btn btn-secondary btn-md" style="background-color: midnightblue;"><a class="text" href="cpl/home_cpl.php">Login</a></button> -->
                        <button type="button" class="btn btn-midnightblue btn-md"><a class="text" href="cpl/home_cpl.php">Login</a></button>

                        <!-- <button class="btn" name="login"><a href="cpl/home_cpl.php">Login</a></button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
        if (isset($_GET['login'])){
            if ($_GET['login'] == 'failed'){
                echo '<Script>alert("Login Failed, Username or password wrong")</script>';
            }
        }
    ?>
    </body>

</html>
