<?php
session_start();    //стартуем сессию

// если в сессии нет массива корзины, то создаем его (первое посещение)
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}

include_once '../config/config.php';                // Инициализация настроек
include_once '../config/db.php';                    //Инициализация базы данных
include_once '../library/mainFunctions.php';        //Основные функции

	//Определяем, с каким контроллером будем работать
    $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'Index';

    //Определяем, с какой функцией будем работать
    $actionName = isset($_GET['action']) ? ucfirst($_GET['action']) : 'index';

    // инициализируем переменную шаблонизатора для количества элементов в корзине
    $smarty->assign('cartCntItems', count($_SESSION['cart']));

   loadPage ($smarty, $controllerName, $actionName);

