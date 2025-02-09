<?php
// Проверяем, что данные были переданы через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $editorContent = $_POST['editorContent'];

    // Выводим полученные данные обратно в форму
    echo '<textarea name="editor">' . $editorContent . '</textarea>';
} else {
}
?>
<form id="emailForm" action="2.php" method="post">
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Визуальный Редактор</title>
    <style>
        #editor {
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
            height: 300px;
            overflow-y: auto;
        }
        #toolbar {
            margin-bottom: 10px;
        }
        .hidden {
            display: none;
        }
        #htmlEditor {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <input type="hidden" name="editorContent" id="editorContent" />
    <input type="email" name="email" required placeholder="Ваш Email" />
    <a href="javascript:void(0);" onclick="submitForm();">Отправить</a>
    <input value="input" type="submit">

<div id="toolbar">
    <select id="headerSelect">
        <option value="">Заголовки</option>
        <option value="h1">H1</option>
        <option value="h2">H2</option>
        <option value="h3">H3</option>
        <option value="h4">H4</option>
        <option value="h5">H5</option>
    </select>
    <select id="fontSelect">
        <option value="">Шрифты</option>
        <option value="Arial">Arial</option>
        <option value="Courier New">Courier New</option>
        <option value="Georgia">Georgia</option>
        <option value="Times New Roman">Times New Roman</option>
        <option value="Verdana">Verdana</option>
    </select>
    <select id="sizeSelect">
        <option value="">Размер</option>
        <option value="1">Маленький</option>
        <option value="3">Средний</option>
        <option value="5">Большой</option>
    </select>
    <button onclick="document.execCommand('bold');">Жирный</button>
    <button onclick="document.execCommand('italic');">Курсив</button>
    <button onclick="document.execCommand('strikeThrough');">Зачеркнутый</button>
    <button onclick="document.execCommand('underline');">Подчеркнутый</button>
    <button onclick="document.execCommand('insertOrderedList');">Нумерованный список</button>
    <button onclick="document.execCommand('insertUnorderedList');">Маркированный список</button>
    <button onclick="document.execCommand('justifyLeft');">Влево</button>
    <button onclick="document.execCommand('justifyCenter');">По центру</button>
    <button onclick="document.execCommand('justifyRight');">Вправо</button>
    <input type="file" id="imageInput" onchange="insertImage(event);" />
    <button onclick="toggleHtmlEditor()">Изменить HTML</button>
</div>

<div id="editor" contenteditable="true"></div>

<div id="htmlEditor" class="hidden">
    <textarea id="htmlCode" rows="10" cols="30"></textarea>
    <a href="javascript:void(0);" onclick="updateEditor();">Обновить</a>
</div>

<script>
    function insertImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.execCommand('insertImage', false, e.target.result);
        };
        reader.readAsDataURL(file);
    }

    function toggleHtmlEditor() {
        const htmlEditor = document.getElementById('htmlEditor');
        const editor = document.getElementById('editor');
        if (htmlEditor.classList.contains('hidden')) {
            document.getElementById('htmlCode').value = editor.innerHTML;
            htmlEditor.classList.remove('hidden');
            editor.classList.add('hidden');
        } else {
            htmlEditor.classList.add('hidden');
            editor.classList.remove('hidden');
        }
    }

    function updateEditor() {
        const htmlCode = document.getElementById('htmlCode').value;
        document.getElementById('editor').innerHTML = htmlCode;
        toggleHtmlEditor();
    }

    function submitForm() {
        const editorContent = document.getElementById('editor').innerHTML;
        document.getElementById('editorContent').value = editorContent;
        document.getElementById('emailForm').submit();
    }

</body>
</html>
<input value="input" type="submit">
</form>
