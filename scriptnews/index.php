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
    ?>
<link rel="stylesheet" href="style/header.css">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/adaptation.css">
<link rel="stylesheet" href="style/body.css">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>управление</title>
<meta charset="UTF-8">
<div class="index">
<?php
$getheaderhtml = file_get_contents("elements/heading.html");
$getheaderhtml = str_replace("{username}", "$username", $getheaderhtml);
echo $getheaderhtml;
?>
<div class="list">
<?php
$fromfiletoview = file_get_contents("data/news.txt"); #папка где записаные данные
$fromfiletoview = explode("\n", $fromfiletoview);
for ($i = 0; $i < count($fromfiletoview); $i++) {
$new = explode("|", $fromfiletoview[$i]);
$textshow = base64_decode($new[2]);
$subject = $new[1];
if ($new[1] == "") {
$subject = "<пусто>";    
}
?>
    <div class="card">
    <h1><a href="edit.php?id=<?=$new[0]?>"><?=$subject?></a></h1>
    <b><p><?=$textshow?></p></b>
    <p><?=$new[3]?></p>
    </div>
    <?php } ?>
</div>
</div>
</div>
<?php
} else {
    echo 'вход запрещен!<script>  window.location.href = "auth.php"; </script>';
}
?>