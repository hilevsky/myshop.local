<?php
/**
 * Работа с таблицей заказов (orders)
 */

/**
 * Создание заказа (без привязки товара)
 *
 * @param string $name
 * @param string $phone
 * @param string $address
 * @return integer id созданного заказа
 */
function makeNewOrder($name, $phone, $address){

    //инициализация переменных
    $userId = $_SESSION['user']['id'];
    $comment = "id пользователя: {$userId}<br>
                Имя: {$name}<br>
                Тел: {$phone}<br>
                Адрес: {$address}";
    $dateCreated = date('Y.m.d H:i:s');
    $userIp = $_SERVER['REMOTE_ADDR'];

    //формирование запроса к БД
    global $db;

    $sql = "INSERT INTO 
            orders (user_id, date_created, date_payment, status, comment, user_ip)
            VALUES ('{$userId}', '{$dateCreated}', null, '0', '{$comment}', '{$userIp}')";

    $rs = mysqli_query($db, $sql);

    //получаем id созданного заказа
    if($rs){
        $sql = "SELECT id
                FROM orders
                ORDER BY id DESC 
                LIMIT 1";

        $rs = mysqli_query($db, $sql);

        //преобразование результатов запроса
        $rs = createSmartyRsArray($rs);

        //возвращаем id созданного товара
        if(isset($rs[0])){
            return $rs[0]['id'];
        }
    }

    return false;
}

/**
 * Получить список заказов с привязкой к товарам для пользователя $userId
 *
 * @param integer $userId   -- id ппользоателя
 * @return array            -- массивзаказов с привязкой к товарам
 */
function getOrdersWithProductsByUser($userId){

    global $db;

    $userId = (int)($userId);
    $sql = "SELECT id, user_id, date_created, date_payment, date_modification, status, comment, user_ip
            FROM orders 
            WHERE user_id = '{$userId}' 
            ORDER BY id DESC";

    $rs = mysqli_query($db, $sql);

    $smartyRs = [];
    while ($row = mysqli_fetch_assoc($rs)){
        $rsChildren = getPurchaseForOrder($row['id']);

        if($rsChildren){
            $row['children'] = $rsChildren;
            $smartyRs[] = $row;
        }
    }
    return $smartyRs;
}
/**
 * Получение данных о заказах и заказчиках для страницы /admin/orders/
 */
function getOrders(){

    global $db;

    $sql = "SELECT o.*, u.name, u.email, u.phone, u.address
            FROM orders AS o
            LEFT JOIN users AS u ON o.user_id = u.id
            ORDER BY id DESC";

    $rs = mysqli_query($db, $sql);

    $smartyRs = [];
    while($row = mysqli_fetch_assoc($rs)){
        $rsChildren = getProductsForOrder($row['id']);

        if($rsChildren){
            $row['children'] = $rsChildren;
            $smartyRs[] = $row;
        }
    }
    return $smartyRs;
}

/**
 * Получить товары заказа
 *
 * @param integer $orderId  -- id заказа
 * @return array            -- массив данных заказа
 */
function getProductsForOrder($orderId){

    global $db;

    $sql = "SELECT * 
            FROM purchase AS pe
            LEFT JOIN products AS ps
            ON pe.product_id = ps.id
            WHERE order_id = '{$orderId}'";

    $rs = mysqli_query($db, $sql);
    return createSmartyRsArray($rs);        //Преобразуем результат запроса в массив
}

/**
 * Обновление статуса заказа в БД, закрыт/нет,
 * страница /admin/orders/
 */
function updateOrderStatus($itemId, $status){
    $status = (int)($status);

    global $db;

    $sql = "UPDATE orders
            SET status = '{$status}'
            WHERE id = '{$itemId}'";

    $rs = mysqli_query($db, $sql);

    return $rs;
}

/**
 * Сохранение в БД даты оплаты заказа
 * страница /admin/orders/, вручную по кнопке "сохранить"
 */
function updateOrderDatePayment($itemId, $datePayment){

    global $db;
    $sql = "UPDATE orders
            SET date_payment = '{$datePayment}'
            WHERE id = '{$itemId}'";

    $rs = mysqli_query($db, $sql);

    return $rs;
}