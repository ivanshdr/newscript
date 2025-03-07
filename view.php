<?php
if ($_POST['section']) {
    // Преобразуем строку в массив
    $dataString = file_get_contents("newscript/data/sections.txt");
    $dataArray = explode(',', $dataString);
    ?>
    <?php foreach ($dataArray as $section) { ?>
        <a href="?section=<?= htmlspecialchars($section) ?>"><?= htmlspecialchars($section) ?></a>
    <?php } ?>
<?php
}

// раздел
$sections = $_GET['section'];
// $datafiles = "newscript/newscript/data/"

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $file = fopen("newscript/data/news.txt", "r");
    while (($line = fgets($file)) !== false) {
        if (substr($line, 0, strlen($id)) === $id) {
            $line = substr($line, strlen($id) + 1);
            $linefull = $id . "|" . $line;
            // echo $linefull;
            $data = explode('|', $linefull);
            $textshow = base64_decode($data[2]);
            
            if ($_POST['name'] . $_POST['email'] . $_POST['comment'] . $_POST['sendcomment']) {
                if (strpos($data[4], "notallowedcom") !== false) {
                    $name = base64_encode(htmlentities($_POST['name']));
                    $email = hash('sha256', base64_encode(htmlentities($_POST['email'])));
                    $ip = hash('sha256', base64_encode(htmlentities($_SERVER['REMOTE_ADDR'])));
                    $text = base64_encode(htmlentities($_POST['comment']));
                    $blacklist = file_get_contents("newscript/data/blacklistcom.txt");
                    
                    if (strpos($blacklist, $ip) !== false || strpos($blacklist, $email) !== false) {
                        echo "вы заблокированы!";
                    } else {
                        $idcomment = uniqid();
                        $createdtime = base64_encode(date("d.m.Y H:i"));
                        $datacomment = $idcomment . "|" . $id . "|" . $name . "|" . $ip . "|" . $email . "|" . $text . "|" . "open" . "|" . $createdtime;
                        $datafromfile = file_get_contents("newscript/data/comments.txt");
                        $datatofile = $datacomment . "\n" . $datafromfile;
                        file_put_contents("newscript/data/comments.txt", $datatofile);
                        echo "комментарий оставлен";
                    }
                } else {
                    echo "no allowed!";
                }
            }
            ?>
            <h1>📰<?= base64_decode($data[1]) ?></h1>
            <p>📅Дата:<?= base64_decode($data[3]) ?></p><?= $textshow ?>
            <?php if (strpos($data[4], "notallowedcom") !== false) { ?>
                <form action="?id=<?= $id ?>" method="post">
                    <h2>📃оставить комментарии</h2>
                    <p><input type="text" placeholder="имя" name="name"></p>
                    <p><input type="email" placeholder="почта" name="email"></p>
                    <p><textarea name="comment"></textarea></p>
                    <input type="submit" name="sendcomment">
                </form>
            <?php
            }
            
            $fromfiletoview = file_get_contents('newscript/data/comments.txt');
            $lines = explode("\n", $fromfiletoview);
            $openEntries = [];
            
            foreach ($lines as $line) {
                $parts = explode('|', $line);
                if ((count($parts) > 1 && trim($parts[1]) === $id) && (count($parts) > 6 && trim($parts[6]) === "open")) {
                    $openEntries[] = $line;
                }
            }
            
            $openOutput = implode("\n", $openEntries);
            $commentslist = explode("\n", $openOutput);
            
            for ($i = 0; $i < count($commentslist); $i++) {
                $commentdata = explode("|", $commentslist[$i]);
                $namecomment = base64_decode($commentdata[2]);
                $textcomment = base64_decode($commentdata[5]);
                $createdtimecomment = base64_decode($commentdata[7]);
                ?>
                <div class="com">
                    <h5><?= $namecomment ?></h5>
                    <p><?= $textcomment ?></p>
                    <p><?= $createdtimecomment ?></p>
                </div>
            <?php
            }
            break;
        }
    }
    fclose($file);
} else {
    $filepath = "newscript/data/news.txt";
    $fromfiletoview = file_get_contents($filepath);
    $lines = explode("\n", $fromfiletoview);
    $publicEntries = [];
    
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        $typesecure = explode(",", $parts[5]);
        if ((count($parts) > 5 && trim($typesecure[0]) === 'public') &&
            (empty($sections) || (count($parts) > 6 && trim($parts[6]) === $sections))) {
            $publicEntries[] = $line;
        }
    }
    
    $publicOutput = implode("\n", $publicEntries);
    $fromfiletoview = $publicOutput;
    $fromfiletoview = explode("\n", $fromfiletoview);
    $page = $_GET['page'];
    $pagenext = $page + 1;
    $pageprevious = $page - 1;
    
    if ($pageprevious == "-1") {
        $pageprevious = "";
    }
    if ($page == "") {
        $page = "1";
        $pagenext = "2";
    }
    if ($page == "0") {
        $page = "1";
    }
    
    $maxnewspages = file_get_contents("newscript/data/pagination.txt");
    $pagenation_2 = $maxnewspages * $page;
    $pagenation = $pagenation_2 - $maxnewspages;
    
    if ($pagenation_2 > count($fromfiletoview)) {
        $pagenation_2 = count($fromfiletoview);
    }
    
    for ($i = $pagenation; $i < $pagenation_2; $i++) {
        $new = explode("|", $fromfiletoview[$i]);
        $textshow = base64_decode($new[2]);
        $subject = base64_decode($new[1]);
        $date = base64_decode($new[4]);
        
        if ($new[1] == "") {
            $subject = "<пусто>";
        }
        ?>
        <div class="post">
            <a href="?id=<?= $new[0] ?>"><h3><?= $subject ?></h3></a>
            <p><?= $date ?></p>
        </div>
    <?php
    }
    
    if ($pageprevious == "") {
        echo "";
    } else {
        echo "<p><a href='?page=$pageprevious'><--</a></p>";
    }
    ?>
    <p><a href="?page=<?= $pagenext ?>">--></a></p>
<?php
}
?>
