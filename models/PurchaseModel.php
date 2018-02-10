<?php
/**
 * Модель для таблицы товаров (purchase)
 */
/**
 * Внесение в БД данных товаров с привязкой к заказу
 *
 * @param integer $orderId  -- id заказа
 * @param array $cart       -- массив корзины
 * @return boolean TRUE     -- в случае успешного добавления в БД
 */
function setPurchaseForOrder($orderId, $cart){

    global $db;

    $sql = "INSERT INTO purchase
            (order_id, product_id, price, amount)
            VALUES ";
    $values = [];
    //формируем массив строк для запроса для каждого товара
    foreach($cart as $item){
        $values[] ="('{$orderId}', '{$item['id']}', '{$item['price']}', '{$item['cnt']}')";
    }

    //преобразуем массив в строку
    $sql .= implode($values, ', ');
    $rs = mysqli_query($db, $sql);

    return $rs;
}

/**
 *  Выборка товаров для вывода на странице пользователя
 *
 */
function getPurchaseForOrder($orderId){

    global $db;

    $sql = "SELECT pe.*, ps.name
            FROM purchase as pe
            JOIN products as ps ON pe.product_id = ps.id
            WHERE pe.order_id = {$orderId}";

    $rs = mysqli_query($db, $sql);
    return createSmartyRsArray($rs);
}