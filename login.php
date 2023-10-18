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
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            border: none;
        }

        a:link {
            color: midnight blue;
            text-decoration: none;
        }
        .mb-3{
            width: 80%;   
            margin: 0 auto;     
        }
    </style>

</head>

<body>
    <?php include "navbar/navbar_before_login.php";?>
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
                    <button class="btn" name="login"><a href="cpl/cpl_page.php">Login</a></button>
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