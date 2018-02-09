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