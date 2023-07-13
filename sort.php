<?php
session_start();

// сортируем многомерный массив пользовательской функцией
 //function cmp($a, $b) {print_r($_SESSION);
   // return strcmp($a["name"], $b["name"]);
//}
//usort($_SESSION, "cmp");

$array_name = [];
 
foreach ($_SESSION as $key => $row)
{
    $array_name[$key] = $row['name'];
}
 
array_multisort($array_name, SORT_ASC, $_SESSION);



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