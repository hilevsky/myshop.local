<?php
/**
 * Created 10.01.2018 13:20 by E. Hilevsky
 */


function loadPage($controllerName, $actionName = 'index'){
    include_once PathPrefix.$controllerName.PathPostfix;

    $function=$actionName.'Action';
    $function();
}