   
    <?php
    if(isset($_GET['id'])){
    $id = $_GET['id'];
    $file = fopen("scriptnews/data/news.txt", "r");
    while (($line = fgets($file)) !== false) {
    if (substr($line, 0, strlen($id)) === $id) {
        $line = substr($line, strlen($id) + 1);
        $linefull = $id. "|". $line;
        // echo $linefull;
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $data = explode('|', $linefull);
        $textshow = base64_decode($data[2]);
        // $textshow = $data[2];
        ?>
        <h1><?=$data[1]?></h1>
        <p><em>дата:<?=$data[3]?></em></p><?=$textshow?>
                <?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        break;
    }
    }
    fclose($file);
    }else {
        ///////////////////////////////////// ?>
<?php
$fromfiletoview = file_get_contents("scriptnews/data/news.txt"); #папка где записаные данные
$fromfiletoview = explode("\n", $fromfiletoview);
for ($i = 0; $i < count($fromfiletoview); $i++) {
$new = explode("|", $fromfiletoview[$i]);
$textshow = base64_decode($new[2]);
$subject = $new[1];
if ($new[1] == "") {
$subject = "<пусто>";    
}
?>
    <h1><a href="?id=<?=$new[0]?>"><?=$subject?></a></h1>
    <b><p><?=$new[4]?></p></b>
    <p><?=$new[3]?></p>
    
    <?php } ?>
<?php } ?>