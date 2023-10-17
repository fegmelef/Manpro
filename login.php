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
            background-color: white;
            max-width: 700px;
            border: 5px solid red;
            border-radius: 8px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
        }

        .login-form .title {
            padding: 15px 10px;
            text-align: center;
            text-shadow: 2px 2px 5px red;
            font-size: 40px;
            image-resolution: 20px 20px;

        }

        img {
            width: 100px;
            height: 100px;
        }


        .login-form .content {
            padding: 25px;
        }

        body {
            background-color: pink;
        }

        a:link {
            color: green;
            background-color: transparent;
            text-decoration: none;
        }

        a:visited {
            color: pink;
            background-color: transparent;
            text-decoration: none;
        }

        a:hover {
            color: red;
            background-color: transparent;
            text-decoration: underline;
        }

        a:active {
            color: yellow;
            background-color: transparent;
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <?php include "welcome_page.php"?>
    <div class="login-form">
        <div class="title bg-primary text-white">
            <img src="logobb.PNG" class="rounded mx-auto" alt="Cinque Terre">
            <br>
            LOGIN
        </div>

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
                    <button class="btn btn-dark" name="login">Login</button>
                    <p>Don't have account? Sign up <a href="registeruser.php">here</a></p>
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