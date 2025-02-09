<?php
session_start();

if (isset($_POST['login'], $_POST['password'], $_POST['loginform'])) {
    // получение данных с формы а именно с auth.php
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    // данные складываются в единую строку 
    $fullloginpass = $login . "|" . $password;
    $data = $fullloginpass;
    $method = "AES-256-CBC";
    $key = $fullloginpass;
    $options = 0;
    $ivo = 'NQh8fd9xjCw5irXf1EfUuw==';
    $iv = base64_decode($ivo);
    
    $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);
    
    // получение данных с файлов из категории data/authdetails.txt
    $getloginsfile = file_get_contents("data/authdetails.txt");
    
    $errorlogin = "<br>неправильный логин или пароль! ";
    
    if ($encryptedData == $getloginsfile) {
        echo "вот я понимаю что работает!";
        $_SESSION['user'] = $login;
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        echo $errorlogin;
    }
} else {
    // сообщение об ошибке, что нет в форме данных
}

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    echo '<script>window.location.href = "index.php";</script>';    
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Авторизация</title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/auth.css">
    <link rel="stylesheet" href="style/body.css">
    <link rel="stylesheet" href="style/adaptation.css">
</head>
<body>
    <div class="index">
        <div class="form">
            <h1 style="font-size:35px;">войти в панель управление</h1>
            <form method="post" action="auth.php">
                <h2>логин</h2>
                <input type="text" class="subject" placeholder="логин" name="login">
                <h2>пароль</h2>
                <input type="password" class="subject" placeholder="пароль" name="password">
                <input type="submit" value="войти" name="loginform">
            </form>
        </div>
    </div>
</body>
</html>
