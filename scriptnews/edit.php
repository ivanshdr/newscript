<?php
session_start();

if (isset($_GET['logout'])) {
    echo "выход";
    session_unset(); // очищает все данные сессии
    session_destroy(); // разрушает сессию
}

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    
    if (isset($_POST['replace'])) {
        $id = $_POST['id'];
        $subject = base64_encode($_POST['subject']);
        $text = base64_encode($_POST['text']);
        $commentsall = $_POST['commentsall'];
        $replace = $_POST['replace'];
        $date = base64_encode($_POST['date']);
        $privacy = $_POST['privacy'];
        $sections = base64_encode($_POST['sections']);
        $smalltext = base64_encode($_POST['smalltext']);
        
        // данные $id, $text, $date вместе для проверки данных
        $fulldata = $id . "|" . $subject . "|" . $text . "|" . $date . "|" . $smalltext . "|" . $privacy . "," . $commentsall . "|" . $sections;
        
        $textsuccess = '<div class="form">успешно сохраненно</div>';
        
        // получение данные с файла data.txt
        $datafromfile = file_get_contents("data/news.txt");
        $datatofile = str_replace($replace, $fulldata, $datafromfile);
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
    <link rel="stylesheet" href="style/edit.css">
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
        <?= $textsuccess ?>
        <div class="form">
        <?php
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $file = fopen("data/news.txt", "r");
            while (($line = fgets($file)) !== false) {
                if (substr($line, 0, strlen($id)) === $id) {
                    $line = substr($line, strlen($id) + 1);
                    $linefull = $id . "|" . $line;
                    $linefull = str_replace("\r", "", $linefull);
                    $linefull = str_replace("\n", "", $linefull);
                    
                    if (isset($_GET['delete'])) {
                        if (isset($_POST['deleteconfirm'])) {
                            echo "<script>window.location.href = 'index.php';</script>";
                            $datafromfile = file_get_contents("data/news.txt");
                            $datatofiledelete = str_replace($linefull, "", $datafromfile);
                            $datatofiledelete = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $datatofiledelete);
                            file_put_contents("data/news.txt", $datatofiledelete);
                        }
        ?>
                        <form action="" method="post">
                            <h1>хотите удалить?</h1>
                            <button name="deleteconfirm">удалить</button>
                        </form>
                        <a style="font-size: 15px;" href="?id=<?= $id ?>">Отменить</a>
        <?php
                    }
                    
                    $data = explode('|', $linefull);
                    $textshow = base64_decode($data[2]);
                    $textshow = str_replace("<br>", "\n", $textshow);
                    $textshow = str_replace("</h1>", "</h1>\n", $textshow);
                    $textshow = str_replace("</h2>", "</h2>\n", $textshow);
                    $textshow = str_replace("</h3>", "</h3>\n", $textshow);
                    $textshow = str_replace("</h4>", "</h4>\n", $textshow);
                    $textshow = str_replace("</h5>", "</h5>\n", $textshow);
                    $textshow = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $textshow);
                    
                    $subjectarticle = base64_decode($data[1]);
                    $descriptionarticle = base64_decode($data[4]);
        ?>
                    <h1 style="font-size:35px;">редактировать статью</h1>
                    <form method="post" id="myForm" href="edit.php">
                        <h2>заголовок</h2>
                        <div class="hidden">
                            <input type="text" name="id" value="<?= $data[0] ?>">
                            <input type="text" name="replace" value="<?= $linefull ?>">
                        </div>
                        <input type="text" class="subject" placeholder="Введите заголовок" name="subject" value="<?= $subjectarticle ?>">
                        <h2>описание</h2>
                        <input type="text" class="subject" placeholder="описание" name="smalltext" value="<?= $descriptionarticle ?>">
                        <h2>Раздел</h2>
                        <?php
                        $dataString = file_get_contents("data/sections.txt");
                        $dataArray = explode(',', $dataString);
                        $sectionarticle = base64_decode($data[6]);
                        ?>
                        <select name="sections">
                            <?php foreach ($dataArray as $key => $section) { ?>
                                <option value="<?= htmlspecialchars($section) ?>" <?php echo ($section === $sectionarticle) ? 'selected' : '' ?>><?= htmlspecialchars($section) ?></option>
                            <?php } ?>
                        </select>
                        <h2>текст</h2>
                        <?php
                        $editorhtml = file_get_contents("elements/editornews.html");
                        $editorhtml = str_replace('<div id="editor" class="diveditor" contenteditable="true"></div>', ('<div id="editor" class="diveditor" contenteditable="true">' . $textshow . '</div>'), $editorhtml);
                        echo $editorhtml;
                        $datecreatearticle = base64_decode($data[3]);
                        ?>
                        <h2>дата</h2>
                        <input type="date" class="dateinput" name="date" value="<?= $datecreatearticle ?>">
                        <?php
                        $dataselectprivacy = explode(',', $data[5]);
                        $securecomments = $dataselectprivacy[1];
                        $securearticle = $dataselectprivacy[0];
                        $privateChecked = ($securearticle === 'private') ? 'checked' : '';
                        $linkChecked = ($securearticle === 'link') ? 'checked' : '';
                        $publicChecked = ($securearticle === 'public') ? 'checked' : '';
                        $publiccomments = ($securecomments === 'allowedcom') ? 'checked' : '';
                        $privatecomments = ($securecomments === 'notallowedcom') ? 'checked' : '';
                        ?>
                        <input type="radio" id="private" name="privacy" value="private" <?php echo $privateChecked; ?>><label for="private">Приватный</label>
                        <input type="radio" id="link" name="privacy" value="link" <?php echo $linkChecked; ?>><label for="link">По ссылке</label>
                        <input type="radio" id="public" name="privacy" value="public" <?php echo $publicChecked; ?>><label for="public">Публичный (по умолчанию)</label>
                        <h4>комментарии</h4>
                        <input type="radio" id="publicom" name="commentsall" value="allowedcom" <?php echo $publiccomments; ?>><label for="publicom">включить комментарии</label>
                        <input type="radio" id="nopublic" name="commentsall" value="notallowedcom" <?php echo $privatecomments; ?>><label for="nopublic">отключить комментарии</label>
                        <br>
                        <input type="submit" value="редактировать"><a href="?id=<?= $id ?>&delete">удалить</a>
                    </form>
        <?php
                    break;
                }
            }
            fclose($file);
        } else {
            echo "тут ничего нет!";
        }
        ?>
        </div>
    </div>
</body>
</html>
<?php
} else {
    echo 'вход запрещен!<script>window.location.href = "auth.php";</script>';
}
?>
