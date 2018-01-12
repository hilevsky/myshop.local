<?php
/**
 * Created 10.01.2018 22:33 by E. Hilevsky
 */

/**
 * Модель для таблицы категорий (categories)
 *
 */

include_once '../config/db.php';


function getAllMainCatsWithChildren(){
    $sql = 'SELECT id, parent_id, name
            FROM categories
            WHERE parent_id=0';

    global $db;

    $rs = mysqli_query($db, $sql);

   while($row = mysqli_fetch_assoc($rs)){
        $smartyRs[] = $row;
    }
    return $smartyRs;
}
