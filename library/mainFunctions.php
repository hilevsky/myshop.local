<?php
/**
 * Created 10.01.2018 13:20 by E. Hilevsky
 */

/**
 *
 * ОСНОВНЫЕ ФУНКЦИИ
 *
 */

/**
 * Формироввание запрашиваемой страницы
 *
 * @param string $controllerName            название контроллера
 * @param string $actionName                название функции обработки страницы
 */
function loadPage($smarty, $controllerName, $actionName = 'index'){
    include_once PathPrefix.$controllerName.PathPostfix;

    $function=$actionName.'Action';
    $function($smarty);
}

/**
 * Загрузка шаблона сайта (макета разметки страниц)
 *
 * @param object $smarty                объект шаблонизатора
 * @param string $templateName          название файла шаблона
 */
function loadTemplate($smarty, $templateName){

    $smarty->display($templateName.TemplatePostfix);
}

/**
 * Функция для отладки. Останавливает работу программы,
 * выводя значение переменной $value
 *
 * @param variant $value  -- переменная, которую необходимо
 * проконтролировать и вывести
 */

function d($value = null, $die = 1){
    echo 'Debug: <br><pre>';
    print_r ($value);
    echo '</pre>';

    if ($die) die;
}

/**
 * Преобразование выборки из БД в ассоциативный массив
 *
 * @param recordset $rs -- набор строк (результат работы SELECT)
 * @return array
 */

function createSmartyRsArray ($rs){
    if (! $rs) return false;
    $smartyRs = [];
    while ($row = mysqli_fetch_assoc($rs)){
        $smartyRs[] = $row;
    }
    return $smartyRs;
}

/**
 * Редирект
 *
 * @param string $url   -- адрес для перенаправления
 */
function redirect($url){
    if(!$url)
        $url = '/';
    header("Location: $url");
    exit;
}