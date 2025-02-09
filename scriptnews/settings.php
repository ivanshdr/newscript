<?php
session_start();
if (isset($_GET['logout'])) {
    echo "выход";
    session_unset(); // очищает все данные сессии
    session_destroy(); // разрушает сессию
}
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];

    if (isset($_POST['login'], $_POST['password'], $_POST['newsettings1'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $fulldata = $login . "|" . $password;
        $data = $fulldata;
        $method = "AES-256-CBC";
        $key = $fulldata;
        $options = 0;
        $ivo = 'NQh8fd9xjCw5irXf1EfUuw==';
        $iv = base64_decode($ivo);
        $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);
        file_put_contents("data/authdetails.txt", $encryptedData);
        echo "логин и пароль изменен!";
    }

    if (isset($_POST['numberinonepage'], $_POST['newsettings3'])) {
        $numberinonepage = $_POST['numberinonepage'];
        if (is_numeric($numberinonepage)) {
            if ($numberinonepage > 0.9) {
                echo "успешно поставленно $numberinonepage";
                file_put_contents("data/pagination.txt", $numberinonepage);
            } else {
                echo "невозможно поставить меньше 1 <br> ваше число: $numberinonepage < 0,99";
            }
        } else {
            echo "принимаются только числа а не символы.";
        }
    }

    if (isset($_POST['sections'], $_POST['newsettings2'])) {
        $sections = $_POST['sections'];
        file_put_contents("data/sections.txt", $sections);
        echo "разделы успешно сохранилось.";
    }

    $getheaderhtml = file_get_contents("elements/heading.html");
    $sectionsgetdata = file_get_contents("data/sections.txt");
    $numberinonepagegetdata = file_get_contents("data/pagination.txt");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>настройки</title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/create.css">
    <link rel="stylesheet" href="style/body.css">
    <link rel="stylesheet" href="style/adaptation.css">
</head>
<body>
    <div class="index">
        <?php
        $getheaderhtml = str_replace("{username}", "$username", $getheaderhtml);
        echo $getheaderhtml;
        ?>
        <div class="form">
            <h1 style="font-size:35px;">настройки</h1>
            <p>смена логина и пароля</p>
            <form method="post" action="">
                <h2>логин</h2>
                <input type="text" class="subject" placeholder="Введите заголовок" required name="login" value="<?= htmlspecialchars($username) ?>">
                <h2>пароль</h2>
                <input type="password" class="subject" placeholder="Введите заголовок" required name="password" value="">
                <div><input type="submit" value="сохранить" name="newsettings1"></div>
            </form>
            <h2>разделы</h2>
            <form method="post" action="">
                <p>каждый раздел разделяеться ","</p>
                <input type="text" class="subject" placeholder="разделы, пример: AI,телефоны,ноутбуки" required name="sections" value="<?= htmlspecialchars($sectionsgetdata) ?>">
                <div><input type="submit" value="сохранить" name="newsettings2"></div>
            </form>
            <h2>максимум количество статей на одной странице (только число)</h2>
            <form method="post" action="">
                <input type="number" class="subject" required name="numberinonepage" value="<?= htmlspecialchars($numberinonepagegetdata) ?>">
                <div><input type="submit" value="сохранить" name="newsettings3"></div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
} else {
    echo 'вход запрещен!<script>window.location.href = "auth.php";</script>';
}
?>
