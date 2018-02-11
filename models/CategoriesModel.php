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

/**
 * Получаем из базы данные для категории по id
 *
 * @param integer $catId  -- id категории
 * @return array    -- массив, содержащий строку категории
 */
function getCatById($catId){
    $catId = (int)($catId);
    $sql = "SELECT id, parent_id, name
            FROM  categories
            WHERE id = '{$catId}'    ";

    global $db;
    $rs = mysqli_query ($db, $sql);
    return mysqli_fetch_assoc($rs);
}

/**
 * Получить все главные категории (которые не дочерние)(для страницы админки)
 *
 * @return array    -- массив категорий
 */
function getAllMainCategories(){

    global $db;

    $sql = "SELECT *
            FROM categories
            WHERE parent_id=0";

    $rs = mysqli_query($db, $sql);

    return createSmartyRsArray($rs);
}

/**
 * Добавление новой категории (страница админки)
 *
 * @param string $catName   -- название категории
 * @param integer $catParentId  -- id родительнской категории
 * @return integer      -- id новой категории
 */
function insertCat($catName, $catParentId = 0){

    global $db;
    $sql = "INSERT INTO categories (parent_id, name)
            VALUES ('{$catParentId}', '{$catName}')";

    mysqli_query($db, $sql);

    //получаем id созданной записи
    $id = mysqli_insert_id($db);

    return $id;
}