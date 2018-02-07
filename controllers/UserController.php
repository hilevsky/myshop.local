<?php
/**
 * Контроллер функций пользователя
 */
//подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/UsersModel.php';

/**
 * AJAX регистрация пользователя
 * Инициализация сессионной переменной ($_SESSION['user'])
 *
 * @return json     -- массив данных нового пользователя
 */
function registerAction(){
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $email = trim($email);

    $pwd1 = isset($_REQUEST['pwd1']) ? $_REQUEST['pwd1'] : null;
    $pwd2 = isset($_REQUEST['pwd2']) ? $_REQUEST['pwd2'] : null;

    $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
    $name = trim($name);
//$massiv=[$email, $pwd1, $pwd2, $phone, $address, $name];
//var_dump($massiv);
    $resData = null;
    $resData = checkRegisterParams($email, $pwd1, $pwd2);
//d($resData);


    if(! $resData && checkUserEmail($email)){
        $resData['success'] = false;
        $resData['message'] = "Пользователь с таким email ('{$email}') уже существует";
    }

    if(! $resData){
        $pwdMD5 = md5($pwd1);

        $userData = registerNewUser($email, $pwdMD5, $name, $phone, $address);
            if($userData['success']) {
                $resData['message'] = "Пользователь успешно зарегистрирован!";
                $resData['success'] = 1;

                $userData = $userData[0];
                $resData['userName'] = $userData['name'] ? $userData['name'] : $userData['email'];
                $resData['userEmail'] = $email;

                $_SESSION['user'] = $userData;
                $_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];
            } else {
                $resData['success'] = 0;
                $resData['message'] = "Ошибка регистрации";
            }
            }
    echo json_encode($resData);
    }

/**
 * Разлогинивание пользователя
 */
function logoutAction(){
    if(isset($_SESSION['user'])){
        unset($_SESSION['user']);
        unset($_SESSION['cart']);
    }
    redirect('/');
}

/**
 * AJAX-авторизация пользователя
 *
 * @return json массив данных пользователя
 */
function loginAction(){
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $email = trim($email);

    $pwd = isset($_REQUEST['pwd']) ? $_REQUEST['pwd'] : null;
    $pwd = trim($pwd);

    $userData = loginUser($email, $pwd);

    if($userData['success']){
        $userData = $userData[0];

        $_SESSION['user'] = $userData;
        $_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];

        $resData = $_SESSION['user'];
        $resData['success'] = 1;

    } else {
        $resData['success'] = 0;
        $resData['message'] = "Неверный логин или пароль";
    }

    echo json_encode($resData);
}