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
            max-width: 500px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            border: none;
        }

        .text {
            color: white;
            text-decoration: none;
        }
        .mb-3{
            width: 80%;   
            margin: 0 auto;     
        }
        .btn {
            width: 20%;
            margin: 0 auto; 
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
        <nav class="navbar navbar-expand-lg navbar fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="row">
                    <div class="col-sm"> 
                        <a class="navbar-brand" href="#">
                            <img src="assets/pcu_black.png" alt="Logo" width="100" height="100" class="logo-img">
                        </a>
                    </div>
                    <div class="col-sm">
                        <a class="navbar-brand" href="#">
                            <img src="assets/pcu_blue.png" alt="Logo" width="150" height="100" class="logo-img">
                        </a>
                    </div>
                    <div>
                </div>
            </div>
        </nav>
    <div class="login-form">
        <div class="content">
            <?=isset($msg) ? '<div class="alert alert-danger">'.$msg.'</div>' : ''?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label"><b>Username</b></label>
                    <input type="text" class="form-control" placeholder="Enter Username" id="enterusername"
                        name="username">
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label"><b>Password</b></label>
                    <input type="password" class="form-control" placeholder="Enter Password" id="enteruserpassword"
                        name="password">
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-secondary btn-md"><a class="text" href="cpl/home_cpl.php">Login</a></button>
                    <!-- <button class="btn" name="login"><a href="cpl/home_cpl.php">Login</a></button> -->
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
    ?></body>

</html>