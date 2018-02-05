<?php
/**
 * Created 13.01.2018 20:33 by E. Hilevsky
 */

/**
 * Модель для вывода таблицы товаров (products)
 */

/**
 * Получаем последние добавленные товары
 *
 * @param integer $limit -- количество товаров
 * @returm array  -- массив товаров
 */

function getLastProducts ($limit = null){

    global $db;

    $sql = "SELECT id, category_id, name, description, price, image, status
            FROM products 
            ORDER BY id DESC";

    if ($limit){
        $sql .= " LIMIT $limit";
    }


    $rs = mysqli_query ($db, $sql);

    return createSmartyRsArray($rs);
}

/**
 * Получить товары для категории $itemId
 *
 * @param integer $itemId   -- id категории
 * @return array            -- массив товаров
 */

function getProductsByCat($itemId){

    $itemId = (int)($itemId);
    $sql = "SELECT id, category_id, name, description, price, image, status
            FROM products 
            WHERE category_id = '{$itemId}'    ";

    global $db;
    $rs = mysqli_query($db, $sql);

    return createSmartyRsArray($rs);
}

/**
 * Получаем информацию о товаре по его id
 * (для страницы товара)
 *
 * @param integer $itemId   -- id товара
 * @return array            -- массив данных товара
 */
function getProductById($itemId){
    $itemId = (int)($itemId);
    $sql =  "SELECT id, category_id, name, description, price, image, status
            FROM products 
            WHERE id = '{$itemId}'    ";

    global $db;
    $rs = mysqli_query($db, $sql);
    return mysqli_fetch_assoc($rs);
}

/**
 * Получаем список товаров и их свойств из БД по списку их id
 * (для страницы корзины)
 *
 * @param array $itemIds   -- массив с id товаров
 * @return array            -- массив данных товаров
 */
function getProductsFromArray($itemIds){

    $strIds = implode($itemIds, ', '); /** Превращаем массив в строку через запятую с пробелом */
    $sql =  "SELECT id, category_id, name, description, price, image, status
            FROM products 
            WHERE id in ({$strIds})    ";

    global $db;
    $rs = mysqli_query($db, $sql);
    return createSmartyRsArray($rs);
}