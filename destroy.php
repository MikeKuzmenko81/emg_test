<?php
session_start();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Закрыть сессию</title>
    </head>
    <body>
        <h1>Контакты удалены ( Сессия закрыта )</h1>
        <a href="index.php">Заполнить новые контакты ( Создать сессию )</a>
    </body>
</html>
<?php
session_destroy();
?>
