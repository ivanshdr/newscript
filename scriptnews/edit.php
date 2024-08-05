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
if(isset($_POST['replace'])){
$id = $_POST['id'];
$subject = $_POST['subject'];
$text = $_POST['text'];
$text = str_replace("\n", "<br>", $text);
// $text = str_replace("\r","",$text);
// $text = str_replace("\n","",$text);
$text = base64_encode($text);
$replace = $_POST['replace'];
$date = $_POST['date'];
// echo "да ладно!!! ";
// данные $id, $text, $date вместе для проверки данных
$fulldata = $id. "|".$subject. "|". $text. "|". $date;
// Echo "<textarea>". $fulldata."</textarea>";
// Echo "<br>КОНЕЦ POST ЗАПРОСА<hr>";
$textsuccess = '<div class="form">успешно сохраненно</div>';
// получение данные с файла data.txt
 $datafromfile = file_get_contents("data/news.txt");
 $datatofile = str_replace($replace,$fulldata,$datafromfile);
 file_put_contents("data/news.txt", $datatofile);
}
?>
<link rel="stylesheet" href="style/header.css">
<link rel="stylesheet" href="style/edit.css">
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
    <?=$textsuccess?>
    <div class="form">
    <?php
    if(isset($_GET['id'])){
    $id = $_GET['id'];
    $file = fopen("data/news.txt", "r");
    while (($line = fgets($file)) !== false) {
    if (substr($line, 0, strlen($id)) === $id) {
        $line = substr($line, strlen($id) + 1);
        $linefull = $id. "|". $line;
        $linefull = str_replace("\r","",$linefull);
        $linefull = str_replace("\n","",$linefull);
        ////////
        if(isset($_GET['delete'])){
        if(isset($_POST['deleteconfirm'])){
        echo "<script>  window.location.href = 'index.php'; </script>";
         $datafromfile = file_get_contents("data/news.txt");
         $datatofiledelete = str_replace($linefull,"",$datafromfile);
         $datatofiledelete = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $datatofiledelete);
         // file_put_contents('file.txt', "$textende");
         file_put_contents("data/news.txt", $datatofiledelete);
        } ?>
        <form action="" method="post">
        <h1>хотите удалить?</h1>
        <button name="deleteconfirm">удалить</button>
        </form>
        <a style="font-size: 15px;" href="?id=<?=$id?>">Отменить</a>
    <?php    }
        
        
        
//        echo $linefull;
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $data = explode('|', $linefull);
        $textshow = base64_decode($data[2]);
        $textshow = str_replace("<br>","\n",$textshow);
         $text = str_replace("</h1>\n<br>","</h1>",$text);
         $textshow = str_replace("</h1>","</h1>\n",$textshow);
         $textshow = str_replace("</h2>","</h2>\n",$textshow);
         $textshow = str_replace("</h3>","</h3>\n",$textshow);
         $textshow = str_replace("</h4>","</h4>\n",$textshow);
         $textshow = str_replace("</h5>","</h5>\n",$textshow);
         $textshow = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $textshow);
        // $textshow = $data[2];
        ?>
        <h1 style="font-size:35px;">редактировать статью</h1>
        <form method="post" href="edit.php">
        <h2>заголовок</h2>
        <div class="hidden">
        <input type="text" name="id" value="<?=$data[0]?>">
        <input type="text" name="replace" value="<?=$linefull?>">
        </div>
        <input type="text" class="subject" placeholder="Введите заголовок" name="subject" value="<?=$data[1]?>">
        <h2>текст</h2>
        <?php
        $editorhtml = file_get_contents("elements/editornews.html");
        $editorhtml = str_replace("</textarea>", ("$textshow"."</textarea>"), $editorhtml);
        echo $editorhtml;
        ?>
        <h2>дата</h2>
        <input type="date" class="dateinput" name="date" value="<?=$data[3]?>">
        <br>
        <input type="submit" value="создать"><a href="?id=<?=$id?>&delete">удалить</a>
        
        </form>
                <?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        break;
    }
    }
    fclose($file);
    }else {
        echo "тут ничего нет!";
    ?>
<?php } ?>
    </div>
</div>
<?php
} else {
    echo 'вход запрещен!<script>  window.location.href = "auth.php"; </script>';
}
?>