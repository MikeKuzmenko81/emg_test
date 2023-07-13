<?php
session_start();
// phpinfo();
// var_dump($_POST);

$showTable = false;
$isCorrect = false;
$flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5;
if (count($_POST) > 0 && array_key_exists('submit', $_POST)) {

    // Проверяю имя, чтобы в строке было не менее 3-х символов
    $isName = true;
    $preName = trim(htmlspecialchars($_POST['name'], $flags));
    if (strlen($preName) >= 3) {
        $name = $preName;
        $isName = true;
    } else {
        $isName = false;
    }

    // Проверяю телефон, чтобы он соответсвовал формату
    $isPhone = true;
    $prePhone = trim(htmlspecialchars($_POST['phone'], $flags));
    if (preg_match("/^\+7\s\([0-9]{3}\)\s[0-9]{3}-[0-9]{2}-[0-9]{2}$/", $prePhone)) {
        $phone = $prePhone;
        $isPhone = true;
    } else {
        $isPhone = false;
    }

    if ($isName && $isPhone) {
        $data = $name . ";" . $phone;
        $dt = hash(md5, $data);
        $_SESSION[$dt]['name'] = $name;
        $_SESSION[$dt]['phone'] = $phone;
        $isCorrect = true;
    }
} else{
    $isCorrect = true;
}

if (count($_SESSION) > 0) {
    $showTable = true;
}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/imask"></script>
        <title>Список контактов</title>
    </head>
    <body>
        <div class="container">
            <h1>Список контактов</h1>
            <main>
                <div class="col-md-6">
                    <form action="/index.php" class="row needs-validation" method="post" novalidate>
                        <div class="col-md-6">
                            <label for="nameValid" class="form-label">Имя</label>
                            <input type="text" class="form-control <?php if(!$isName && !$isCorrect){ echo "is-invalid";}?>" name="name" 
                                   value="<?php if ($isName && !$isCorrect){echo $name;}?>" 
                                   id="nameValid" placeholder="Введите Имя" required>
                            <div class="invalid-feedback">
                                Минимальная длина имени 3 символа
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control <?php if(!$isPhone && !$isCorrect){ echo "is-invalid";}?>" name="phone" 
                                   value="<?php if ($isPhone && !$isCorrect){echo $phone;}?>"
                                   id="phone" pattern="+7 ([0-9]{3}) [0-9]{3}-[0-9]{2}-[0-9]{2}" placeholder="+7 (xxx) xxx-xx-xx" required>
                            <div class="invalid-feedback">
                                Телефон должен иметь вид +7 (xxx) xxx-xx-xx
                            </div>
                            <br>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary" id="add_button" name="submit" type="submit">Добавить контакт</button>
                        </div>
                    </form>
                </div>
                <script>
                    // Обработка формата телефона в браузере
                    var phoneMask = IMask(
                            document.getElementById('phone'), {
                        mask: '+{7} (000) 000-00-00'
                    });
                </script>


                <?php if ($showTable): ?>
                    <div class="table-responsive-md">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Имя <button id="sort">Сортировать</button> </th>
                                    <th scope="col">Телефон</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="content">

                                <?php
                                if (count($_SESSION) > 0) {
                                    
                                    foreach ($_SESSION as $key => $val) {
                                        ?>
                                        <tr class="table-light">
                                            <td>
                                                <?php echo $val['name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $val['phone']; ?>
                                            </td>
                                            <td>
                                                <button onclick="delElem(this)" id="<?php echo $key; ?>">Удалить</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?> 

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class"col-md-6">
                            <p>
                                <a href="destroy.php">Удалить все контакты ( Закрыть сессию )</a>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
        <script>
            function sortTable() {
                var xhr = new XMLHttpRequest();
                xhr.onload = function() {
                    if (xhr.status === 200){
                        // выводит html полученный через ajax
                        document.getElementById('content').innerHTML = xhr.responseText;
                    }
                };
                xhr.withCredentials = true;
                xhr.open('POST', '/sort.php', true);
                xhr.send(null);
            }
            var sortEv = document.getElementById('sort');
            sortEv.onclick = sortTable;
            
            
            ///////////////////////////////////////
            function delElem(obj){
                var hash = obj.id;
                var xhr = new XMLHttpRequest();
                xhr.onload = function() {
                    if (xhr.status === 200){
                        // выводит html полученный через ajax
                        document.getElementById('content').innerHTML = xhr.responseText;
                    }
                };
                xhr.withCredentials = true;
                xhr.open('POST', '/delete.php?el='+ hash, true);
                xhr.send(null);
            }
            var delEv = document.getElementById('delete');
            delEv.onclic = delElem;
        </script>
    </body>
</html>