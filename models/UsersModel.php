<?php
/**
 * Модель для работы с таблицей пользователей
 */
/**
 * Регистрация нового пользователя
 *
 * @param string $email     -- почта
 * @param string $pwdMD5    -- пароль, зашифрованный в MD5
 * @param string $name      -- имя пользователя
 * @param string $phone     -- телефон
 * @param string $address   -- адрес пользователя
 * @return array            -- массив данных нового пользователя
 */

function registerNewUser($email, $pwdMD5, $name, $phone, $address){

    global $db;

    $email = /*htmlspecialchars*/(mysqli_real_escape_string($db, $email));
    $name = htmlspecialchars(mysqli_real_escape_string($db, $name));
    $phone = htmlspecialchars(mysqli_real_escape_string($db, $phone));
    $address = htmlspecialchars(mysqli_real_escape_string($db, $address));
//$massiv=[$email, $pwdMD5, $phone, $address, $name];
//var_dump($massiv);
    $sql = "INSERT INTO 
              users (email, pwd, name, phone, address)
              VALUES ('{$email}', '{$pwdMD5}', '{$name}', '{$phone}', '{$address}')";
//d($sql);
    $rs = mysqli_query($db, $sql);
//var_dump($rs);
    if(!$rs){
        $rs['success'] = 0;
    } else {

        $sql = "SELECT id, email, pwd, name, phone, address FROM users
                WHERE email='{$email}' and pwd='{$pwdMD5}'
                LIMIT 1";

        $rs = mysqli_query($db, $sql);
        $rs = createSmartyRsArray($rs);

        if(isset($rs[0])){
            $rs['success'] = 1;
        } else {
            $rs['success'] = 0;
        }
    }
//var_dump($rs);
    return $rs;
}

/**
 * Проверка параметров для регистрации пользователя (приходят из заполненной формы)
 *
 * @param string $email     -- email
 * @param string $pwd1      -- пароль
 * @param string $pwd2      --повтор пароля
 * @return array            -- результат проверки
 */
function checkRegisterParams($email, $pwd1, $pwd2){

    $res = [];


    if(! $email){
        $res['success'] = false;
        $res['message'] = 'Введите email';
    }
    elseif(! $pwd1){
        $res['success'] = false;
        $res['message'] = 'Введите пароль';
    }
    elseif(! $pwd2){
        $res['success'] = false;
        $res['message'] = 'Введите повтор пароля';
    }
    elseif($pwd1 != $pwd2){
        $res['success'] = false;
        $res['message'] = 'Пароли не совпадают';
    }

    return $res;
}


/**
 * Проверка, существует ли регистрирующийся пользователь с таким email
 *
 * @param string    -- email пользователя
 * @return array    -- массив со строкой из таблицы Users либо пустой массив
 */
function checkUserEmail($email){

    global $db;
    $email = mysqli_real_escape_string($db, $email);
    $sql = "SELECT id FROM users WHERE email = '{$email}'";

    $rs = mysqli_query($db, $sql);
    $rs = createSmartyRsArray($rs);

    return $rs;
}

/**
 * Авторизация пользователя
 *
 * @param string $email адрес электронной почты (он же логин)
 * @param string $pwd пароль
 * @return array массив данных пользователя
 */
function loginUser($email, $pwd){

    global $db;

    $email = htmlspecialchars(mysqli_real_escape_string($db, $email));
    $pwd = md5($pwd);

    $sql = "SELECT id, email, pwd, name, phone, address 
            FROM users
            WHERE email = '{$email}' and pwd = '{$pwd}'
            LIMIT 1";
//d($sql);
    $rs = mysqli_query($db, $sql);

    $rs = createSmartyRsArray($rs);

    if(isset($rs[0])){
        $rs['success'] = 1;
    } else {
        $rs['success'] = 0;
    }

    return $rs;
}
/**
 * Изменение данных пользователя
 *
 * @param string $name      -- имя
 * @param string $phone     -- телефон
 * @param string $address   -- адрес
 * @param string $pwd1      -- новый пароль
 * @param string $pwd2      -- повтор нового пароля
 * @param string $curPwd    -- текущий пароль
 * @return boolean TRUE в случае успеха
 */
function updateUserData($name, $phone, $address, $pwd1, $pwd2, $curPwd){

    global $db;

    $email = /*htmlspecialchars*/(mysqli_real_escape_string($db, $_SESSION['user']['email']));
    $name = htmlspecialchars(mysqli_real_escape_string($db, $name));
    $phone = htmlspecialchars(mysqli_real_escape_string($db, $phone));
    $address = htmlspecialchars(mysqli_real_escape_string($db, $address));
    $pwd1 = trim($pwd1);
    $pwd2 = trim($pwd2);

    $newPwd = null;
    if($pwd1 &&($pwd1==$pwd2)){
        $newPwd = md5($pwd1);
    }

    $sql = "UPDATE users SET ";

    if($newPwd){
        $sql .= "pwd = '{$newPwd}',";
    }

    $sql .= "
              name = '{$name}',
              phone = '{$phone}',
              address = '{$address}'
              WHERE
              email = '{$email}' AND pwd = '{$curPwd}'
              LIMIT 1
              ";

    $rs = mysqli_query($db, $sql);

    return $rs;
}

/**
 * Получить данные заказа текущего пользователя
 *
 * @return array массив заказов с привязкой к продуктам
 */
function getCurUserOrders(){
    $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
    $rs = getOrdersWithProductsByUser($userId);

    return $rs;
}