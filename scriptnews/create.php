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
// данные с формы
$subject = $_POST['subject'];
$text = $_POST['text'];
$date = $_POST['date'];
// $text = '<p>'.str_replace("\n\n", '</p><p>', $text).'</p>';
 $text = str_replace("\n", '<br>', $text);
 $text = str_replace("\n","",$text);
$text = str_replace("\r","",$text);
$text = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $text);
$text = str_replace("</h1><br>","</h1>",$text);
$text = str_replace("</h2><br>","</h2>",$text);
$text = str_replace("</h3><br>","</h3>",$text);
$text = str_replace("</h4><br>","</h4>",$text);
$text = str_replace("</h5><br>","</h5>",$text);
///////
$text = str_replace("<br><h1>","<h1>",$text);
$text = str_replace("<br><h2>","<h2>",$text);
$text = str_replace("<br><h3>","<h3>",$text);
$text = str_replace("<br><h4>","<h4>",$text);
$text = str_replace("<br><h5>","<h5>",$text);
$text = base64_encode($text);
// $date = date('d/m/Y');
$id = uniqid(true);
if(isset($_POST['text'])){
// данные $login,$password вместе для проверки данных
$textdate = $id. "|". $subject. "|". $text. "|". $date;
// получение данные с файла data.txt
$datafromfile = file_get_contents("data/news.txt");
$datatofile = $textdate. "\n". $datafromfile;
file_put_contents("data/news.txt", $datatofile);
}
?>
<link rel="stylesheet" href="style/header.css">
<link rel="stylesheet" href="style/create.css">
<link rel="stylesheet" href="style/body.css">
<link rel="stylesheet" href="style/adaptation.css">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>управление</title>
<meta charset="UTF-8">
<div class="index">
<?php
$getheaderhtml = file_get_contents("elements/heading.html");
$getheaderhtml = str_replace("{username}", "$username", $getheaderhtml);
echo $getheaderhtml;
?>
    <div class="form">
        <h1 style="font-size:35px;">создать статью</h1>
        <form method="post" href="view.php">
        <h2>заголовок</h2>
        <input type="text" class="subject" placeholder="Введите заголовок" name="subject">
        <h2>текст</h2>
        <?php
        $getheaderhtml = file_get_contents("elements/editornews.html");
        echo $getheaderhtml;
        ?>
        <h2>дата</h2>
        <input type="date" class="dateinput" name="date" value="<?=date('Y-m-d');?>">
        <h4>другие настройки</h4>
        <input type="submit" value="создать">
        
        </form>
    </div>
</div>
<?php
} else {
    echo 'вход запрещен!<script>  window.location.href = "auth.php"; </script>';
}
?>