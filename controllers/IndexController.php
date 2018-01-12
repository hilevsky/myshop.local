<?php
/**
 * Created 10.01.2018 12:01 by E. Hilevsky
 */
//Подключаем модели
include_once '../models/CategoriesModel.php';



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

    $smarty->assign('pageTitle', 'Главная страница сайта');
    $smarty->assign('rsCategories', $rsCategories);

    loadTemplate($smarty, "header");
    loadTemplate($smarty, "index");
    loadTemplate($smarty, "footer");
}