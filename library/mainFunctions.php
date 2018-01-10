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
function loadPage($controllerName, $actionName = 'index'){
    include_once PathPrefix.$controllerName.PathPostfix;

    $function=$actionName.'Action';
    $function();
}