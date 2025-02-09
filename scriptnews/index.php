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
            <?php
            $fromfiletoview = file_get_contents("data/news.txt"); // папка где записаны данные
            $fromfiletoview = explode("\n", $fromfiletoview);
            foreach ($fromfiletoview as $line) {
                $new = explode("|", $line);
                if (count($new) < 5) continue; // Пропускаем некорректные строки
                $textshow = base64_decode($new[4]);
                $subject = base64_decode($new[1]);
                $subject = empty($subject) ? "<пусто>" : $subject;
            ?>
                <div class="card">
                    <h1><a href="edit.php?id=<?= $new[0] ?>"><?= htmlspecialchars($subject) ?></a></h1>
                    <b><p><?= htmlspecialchars($textshow) ?></p></b>
                    <p><?= htmlspecialchars(base64_decode($new[3])) ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
<?php
} else {
    echo 'вход запрещен!<script>window.location.href = "auth.php";</script>';
}
?>
