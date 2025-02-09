<?php
session_start();
if (isset($_GET['logout'])) {
    echo "выход";
    session_unset(); // очищает все данные сессии
    session_destroy(); // разрушает сессию
}
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];

    // данные с формы
    $subject = base64_encode($_POST['subject']);
    $text = base64_encode($_POST['text']);
    $date = base64_encode($_POST['date']);
    $commentsall = $_POST['commentsall'];
    $privacy = $_POST['privacy'];
    $sections = base64_encode($_POST['sections']);
    $smalltext = base64_encode($_POST['smalltext']);

    $text = str_replace("\n", '<br>', $text);
    $text = str_replace("\r", "", $text);
    $text = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $text);
    $text = str_replace(["</h1><br>", "</h2><br>", "</h3><br>", "</h4><br>", "</h5><br>"], ["</h1>", "</h2>", "</h3>", "</h4>", "</h5>"], $text);
    $text = str_replace(["<br><h1>", "<br><h2>", "<br><h3>", "<br><h4>", "<br><h5>"], ["<h1>", "<h2>", "<h3>", "<h4>", "<h5>"], $text);
    $text = base64_encode($text);

    $id = uniqid(true);
    if (isset($_POST['text'])) {
        $textdate = $id . "|" . $subject . "|" . $text . "|" . $date . "|" . $smalltext . "|" . $privacy . "," . $commentsall . "|" . $sections;
        $datafromfile = file_get_contents("data/news.txt");
        $datatofile = $textdate . "\n" . $datafromfile;
        file_put_contents("data/news.txt", $datatofile);
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>управление</title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/editor.css">
    <link rel="stylesheet" href="style/create.css">
    <link rel="stylesheet" href="style/body.css">
    <link rel="stylesheet" href="style/adaptation.css">
</head>
<body>
    <div class="index">
        <?php
        $getheaderhtml = file_get_contents("elements/heading.html");
        $getheaderhtml = str_replace("{username}", "$username", $getheaderhtml);
        echo $getheaderhtml;
        ?>
        <div class="form">
            <h1 style="font-size:35px;">создать статью</h1>
            <form method="post" id="myForm" action="create.php">
                <h2>заголовок</h2>
                <input type="text" class="subject" placeholder="Введите заголовок" name="subject">
                <h2>раздел</h2>
                <?php
                $dataString = file_get_contents("data/sections.txt");
                $dataArray = explode(',', $dataString);
                ?>
                <select name="sections">
                    <?php foreach ($dataArray as $section) { ?>
                        <option value="<?= htmlspecialchars($section) ?>"><?= htmlspecialchars($section) ?></option>
                    <?php } ?>
                </select>
                <h2>описание</h2>
                <input type="text" class="subject" placeholder="описание" name="smalltext">
                <h2>текст</h2>
                <?php
                $getheaderhtml = file_get_contents("elements/editornews.html");
                echo $getheaderhtml;
                ?>
                <h2>дата</h2>
                <input type="date" class="dateinput" name="date" value="<?= date('Y-m-d'); ?>">
                <h4>Выберите приватность статьи</h4>
                <input type="radio" id="private" name="privacy" value="private"><label for="private">Приватный</label>
                <input type="radio" id="link" name="privacy" value="link"><label for="link">По ссылке</label>
                <input type="radio" id="public" name="privacy" value="public" checked><label for="public">Публичный (по умолчанию)</label>
                <h4>комметарии</h4>
                <input type="radio" id="publicom" name="commentsall" value="allowedcom" checked><label for="publicom">включить комментарии</label>
                <input type="radio" id="nopublic" name="commentsall" value="notallowedcom"><label for="nopublic">отключить комментарии</label>
                <br>
                <input type="submit" value="создать">
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
