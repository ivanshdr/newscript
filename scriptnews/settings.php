<?php
session_start();
if(isset($_GET['logout'])){
    echo "выход";
    session_unset(); // очищает все данные сессии
    session_destroy(); // разрушает сессию
}
if(isset($_SESSION['user'])){
    $username = $_SESSION['user'];
    // echo $username;
if(isset($_POST['login'],$_POST['password'],$_POST['newsettings1'])){
    $login = $_POST['login'];
    $password = $_POST['password'];
    $fulldata = $login. "|". $password;
    // $encryptedData = openssl_encrypt($fulldata, 'AES-256-CBC', $fulldata, OPENSSL_RAW_DATA);
    $data = $fulldata;
    $method = "AES-256-CBC";
    $key = $fulldata;
    $options = 0;
  //  $iv = '1234567891011111';
   $ivo = 'NQh8fd9xjCw5irXf1EfUuw==';
    $iv = base64_decode($ivo);
    $encryptedData = openssl_encrypt($data, $method, $key, $options,$iv);
    file_put_contents("data/authdetails.txt", $encryptedData);
    echo "логин и пароль изменен!";
    } //else {    }
/*    $detailogin = file_get_contents("data/authdetails.txt");
    $data = $fulldata;
    $method = "AES-256-CBC";
    $key = $fulldata;
    $options = 0;
    $iv = '1234567891011111';
    $decryptData = openssl_encrypt($data, $method, $key, $options,$iv);
    $dataexplodeacc = explode("|", $detailogin); */
?>
<link rel="stylesheet" href="style/header.css">
<link rel="stylesheet" href="style/create.css">
<link rel="stylesheet" href="style/body.css">
<link rel="stylesheet" href="style/adaptation.css">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>настройки</title>
<meta charset="UTF-8">
<div class="index">
<?php
$getheaderhtml = file_get_contents("elements/heading.html");
$getheaderhtml = str_replace("{username}", "$username", $getheaderhtml);
echo $getheaderhtml;
?>
    <div class="form">
        <h1 style="font-size:35px;">настройки</h1>
        <form method="post" href="view.php">
        <h2>логин</h2>
        <input type="text" class="subject" placeholder="Введите заголовок" required name="login" value="<?=$username?>">
        <h2>пароль</h2>
        <input type="password" class="subject" placeholder="Введите заголовок" required name="password" value="">
        <div><input type="submit" value="сохранить" name="newsettings1"></div>
        </form>
    </div>
</div>
<?php
} else {
    echo 'вход запрещен!<script>  window.location.href = "auth.php"; </script>';
}
?>
