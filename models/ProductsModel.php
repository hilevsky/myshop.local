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