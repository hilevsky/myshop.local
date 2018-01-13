<?php
/**
 * Created 10.01.2018 12:01 by E. Hilevsky
 */
//Подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';



/**
 *
 * КОНТРОЛЛЕР ГЛАВНОЙ СТРАНИЦЫ
 *
 */

/*Тестовая функция, которая выводит,
из какого контроллера какая функция была вызвана*/
function testAction(){
    echo 'IndexController.php > testAction';
}

/**
 * Формирование главной страницы сайта
 *
 * @param object $smarty            шаблонизатор
 */

function indexAction($smarty){

    $rsCategories = getAllMainCatsWithChildren();
    $rsProducts = getLastProducts (16);

    $smarty->assign('pageTitle', 'Главная страница сайта');
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProducts', $rsProducts);

    loadTemplate($smarty, "header");
    loadTemplate($smarty, "index");
    loadTemplate($smarty, "footer");
}