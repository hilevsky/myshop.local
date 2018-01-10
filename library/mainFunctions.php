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