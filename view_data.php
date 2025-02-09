<?php  
       // раздел
        $sections = $_GET['section'];
        if(isset($_GET['id'])){
        $id = $_GET['id'];
        $file = fopen("newscript/data/news.txt", "r");
        while (($line = fgets($file)) !== false) {
        if (substr($line, 0, strlen($id)) === $id) {
        $line = substr($line, strlen($id) + 1);
        $linefull = $id. "|". $line;
        // echo $linefull;
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $data = explode('|', $linefull);
        $titlehtml = base64_decode($data[1]);
        ?>
<?php  } } } ?>
