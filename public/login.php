<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location:index.php");
    exit();
}
if ($_POST) {
    include("../db-baglanti/db_baglantim.php");

    $username = $_POST['username'];
    $password = $_POST['password'];
    $findUserQuery = $baglanti->prepare("SELECT id, username FROM users WHERE username=:username and password=:password");
    $findUserQuery->execute(["username" => $username, "password" => $password]);
    $user = $findUserQuery->fetch();
    if (!empty($user)) {

        $_SESSION['user'] = $user;
        header("Location:index.php");
    }

}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Screen</title>

    <style>
        body {
            margin: 0;
            background-color: #eceefb;

        }

        .page-content {
            height: calc(100vh - 84px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            border: 1px solid #2f3032;
            padding: 16px;
            align-items: center;
            background-color: #2f3032;
            border-radius: 15px;
            max-width: 320px;
            height: 400px;
            width: 100%;

        }

        .title {
            font-family: 'OpenSans', sans-serif;
            color: white;
            padding: 16px;
            text-align: center;
        }

        input {
            border-style: none;
            border-bottom: 1px solid white;
            padding: 24px 0;
            background-color: #2f3032;
            color: white;
            width: 100%;
        }

        input:focus {
            outline: 0;
        }

        .submit button {
            border: 2px solid white;
            background-color: #2f3032;
            color: white;
            width: 200px;
            padding: 16px;


        }

        .submit button:hover {
            background-color: white;
            color: black;
            transition-duration: 1s;
            transition-property: all;
            transition-timing-function: ease-in;
        }

        .submit {
            margin-top: 24px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="page-content">
    <div class="login-container">
        <div class="title"><h2>Login</h2></div>
        <form action="login.php" method="post">
            <div class="login">
                <div class="username"><input type="text" placeholder="Username" name="username"></div>
                <div class="password"><input type="password" placeholder="Password" name="password"></div>
            </div>
            <div class="submit" name="login">
                <button>Giriş</button>
            </div>
        </form>
    </div>
</div>


</body>
</html>