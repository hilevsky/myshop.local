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

        $sql = "SELECT email, pwd, name, phone, address FROM users
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

    $res = array();


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