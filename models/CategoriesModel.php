<?php
/**
 * Created 10.01.2018 22:33 by E. Hilevsky
 */

/**
 * Модель для таблицы категорий (categories)
 *
 */

/**
 * Получение дочерних категорий для категории $catId
 *
 * @param integer $catId   -- ID категории
 * @return array  -- массив дочерних категорий
 */

function getChildrenForCat($catId){
    $sql = "SELECT id, parent_id, name
            FROM categories
            WHERE parent_id='{$catId}'";

    global $db;

    $rs = mysqli_query($db, $sql);

    return createSmartyRsArray ($rs);       //Преобразуем результат запроса в массив
}




/**
 * Получить главные категории товаров с привязкой дочерних
 *
 * @return array массив категорий
 */

function getAllMainCatsWithChildren(){
    $sql = 'SELECT id, parent_id, name
            FROM categories
            WHERE parent_id=0';

    global $db;

    $rs = mysqli_query($db, $sql);

   while($row = mysqli_fetch_assoc($rs)){

       $rsChildren = getChildrenForCat($row['id']);
       if($rsChildren){
           $row['children'] = $rsChildren;
       }

        $smartyRs[] = $row;
    }
    return $smartyRs;
}
