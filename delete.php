<?php
session_start();

// Удаляем выбранный элемент
if (is_array($_SESSION[$_GET['el']])) {
    unset($_SESSION[$_GET['el']]);
}

// подготавливаем html для возвращения
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