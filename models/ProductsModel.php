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
            WHERE status = 1
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
            WHERE category_id = '{$itemId}' AND status = 1   ";

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
            WHERE id = '{$itemId}'  AND status = 1  ";

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

/**
 * Получение всех товаров (для страницы редактирования товаров /admin/products/)
 *
 * @return array -- массив категорий
 */
function getProducts(){

    global $db;

    $sql = "SELECT * 
            FROM products
            ORDER BY category_id";

    $rs = mysqli_query($db, $sql);

    return createSmartyRsArray($rs);
}

/**
 * Добавление нового товара (для страницы редактирования товаров /admin/products/)
 *
 * @param string $itemName      -- название товара
 * @param integer $itemPrice    -- цена
 * @param string $itemDesc      -- описание
 * @param integer $itemCat      -- цена
 * @return integer      -- результат выполнения запроса к БД
 */
function insertProduct($itemName, $itemPrice, $itemDesc, $itemCat){

    global $db;

    $sql = "INSERT INTO products
            SET 
            name = '{$itemName}',
            price = '{$itemPrice}',
            description = '{$itemDesc}',
            category_id = '{$itemCat}'";
//d($sql);
    $rs = mysqli_query($db, $sql);

    return $rs;
}

/**
 *  Редактирование товара (нв странице /admin/products/)
 */
function updateProduct($itemId, $itemName, $itemPrice, $itemStatus, $itemDesc, $itemCat, $newFileName = null){

    $set=[];

    if($itemName){
        $set[] = "name = '{$itemName}'";
    }

    if($itemPrice > 0){
        $set[] = "price = '{$itemPrice}'";
    }

    if($itemStatus !== null){
        $set[] = "status = '{$itemStatus}'";
    }

    if($itemDesc){
        $set[] = "description = '{$itemDesc}'";
    }

    if($itemCat){
        $set[] = "category_id = '{$itemCat}'";
    }

    if($newFileName){
        $set[] = "image = '{$newFileName}'";
    }

    $setStr = implode($set,", ");

    global $db;

    $sql = "UPDATE products
            SET {$setStr}
            WHERE id = '{$itemId}'";

    $rs = mysqli_query($db, $sql);

    return $rs;
}
/**
 * Добавление названия файла с картинкой в БД
 */
function updateProductImage($itemId, $newFileName){

    $rs = updateProduct($itemId, null, null, null,
        null, null, $newFileName);

    return $rs;
}