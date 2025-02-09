<?php
session_start();
if (isset($_GET['logout'])) {
    echo "выход";
    session_unset(); // очищает все данные сессии
    session_destroy(); // разрушает сессию
}
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>управление</title>
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/adaptation.css">
    <link rel="stylesheet" href="style/body.css">
</head>
<body>
    <div class="index">
        <?php
        $getheaderhtml = file_get_contents("elements/heading.html");
        $getheaderhtml = str_replace("{username}", "$username", $getheaderhtml);
        echo $getheaderhtml;
        ?>
        <div class="list">
            <div class="card">
                <h2>узнать зашифрофрованное email/IP-адрес</h2>
                <form method='post'>
                    <input type='text' name='viewhashdatacom' placeholder='IP-адрес или почта'>
                    <input type='submit' name='viewdatacomm' value='узнать'>
                </form>
                <h2>разблокировка пользователя</h2>
                <form method='post'>
                    <input type='text' name='unblock' placeholder='зашифрованный IP-адрес или почта'>
                    <input type='submit' name='buttonunblock' value='раблокировать пользователя'>
                </form>
                <h2>просмотр комментариев</h2>
                <form method="get">
                    <select name="id">
                        <?php 
                        foreach (file('data/news.txt') as $line) {
                            list($id, $name) = explode('|', trim($line));
                            $name = base64_decode($name);
                            echo "<option value=\"$id\">$name</option>";
                        }
                        ?>
                    </select>
                    <input type="submit">
                </form>
            </div>
            <?php
            $datacomments = file_get_contents('data/comments.txt');
            if (isset($_POST['delete'])) {
                $replace = $_POST['replace'];
                echo "<form method='post'><input type='hidden' name='delete' value=''><input type='hidden' name='replace' value=\"$replace\"> точно ли удалить? <input type='submit' value='удаление' name='confirmdelete'></form>";
                if ($_POST['confirmdelete'] && isset($_POST['replace'])) {
                    $replace = $_POST['replace'];
                    $datafilecom = file_get_contents("data/comments.txt");
                    $datareplace = str_replace($replace, "", $datafilecom);
                    $datareplace = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $datareplace);
                    file_put_contents("data/comments.txt", $datareplace);
                    echo "удаленно";
                    echo $_POST['replace'];
                }
            }
            if (isset($_POST['viewhashdatacom']) && ($_POST['viewdatacomm'])) {
                $unblockdata = hash('sha256', base64_encode(htmlentities($_POST['viewhashdatacom'])));
                echo $unblockdata;
            }
            if (isset($_POST['unblock']) && ($_POST['buttonunblock'])) {
                $unblockdata = $_POST['unblock'];
                $replacedatacomments = str_replace($unblockdata, "", file_get_contents('data/blacklistcom.txt'));
                $replacedatacomments = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $replacedatacomments);
                file_put_contents('data/blacklistcom.txt', $replacedatacomments);
            }
            if (isset($_POST['block'])) {
                $replace = $_POST['replace'];
                $datablock = explode("|", $replace);
                $datafull = $datablock[3] . ',' . $datablock[4];
                echo "<form method='post'><input type='hidden' name='block' value=''><input type='hidden' name='fulldatablock' value='$datafull'><h2>pers. data (encrypted)</h2><hr>ip:" . $datablock[3] . "<input type='submit' value='заблокировать IP-адрес' name='blockip'><hr>email:" . $datablock[4] . "<input type='submit' value='заблокировать почту' name='blockemail'></form>";
            }
            if (isset($_POST['blockip']) || isset($_POST['blockemail'])) {
                $dataor = isset($_POST['blockip']) ? 0 : 1;
                $blockdata = $_POST['fulldatablock'];
                $blockrealdata = explode(',', $blockdata);
                echo $blockrealdata[$dataor];
                $fp = fopen('data/blacklistcom.txt', 'a');
                fwrite($fp, $blockrealdata[$dataor] . PHP_EOL);
                fclose($fp);
            }
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $filePath = 'data/comments.txt';
                $data = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $fromfiletoview = array_filter($data, fn($line) => strpos($line, $id) !== false);
                $fromfiletoview = implode("\n", $fromfiletoview);
            } else {
                $fromfiletoview = file_get_contents("data/comments.txt");
            }
            $fromfiletoview = explode("\n", $fromfiletoview);
            foreach ($fromfiletoview as $commentLine) {
                $comment = explode("|", $commentLine);
                if (count($comment) < 8) continue; // Пропускаем некорректные строки
                $subject = $comment[1];
                $idcomment = $comment[0];
                $namecomment = base64_decode($comment[2]);
                $textcomment = base64_decode($comment[5]);
                $timecreatedcomment = base64_decode($comment[7]);
            ?>
                <div class="card">
                    <form action="comments.php" method="post">
                        <input type="hidden" value="<?= $idcomment ?>" name="id">
                        <input type="hidden" value="<?= $commentLine ?>" name="replace">
                        <h1>написал: <?= $namecomment ?></h1>
                        <b><p><?= $textcomment ?></p></b>
                        <p><?= $timecreatedcomment ?></p>
                        <input type="submit" value="удалить" name="delete">
                        <input type="submit" value="заблокировать отравителя" name="block">
                    </form>
                </div>
            <?php
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
