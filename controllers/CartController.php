<?php
/**
 * Created 01.02.2018 17:37 by E. Hilevsky
 */
/**
 * CartController.php
 *
 * Контроллер работы с корзиной товара (/cart/)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/**
 * Добаление товара в корзину
 *
 * @param integer id GET параметр   -- id добавляемого товара
 * @return json                     -- информация об операции (успех/неуспех, кол-во товаров в корзине)
 */

function addtocartAction(){
    $itemId = isset($_GET['id']) ? (int)($_GET['id'] ) : null;
    if(!$itemId)
        return false;

    $resData = array();

    if(isset($_SESSION['cart']) && array_search($itemId, $_SESSION['cart']) === false){
        $_SESSION['cart'][] = $itemId;
        $resData['cntItems'] = count($_SESSION['cart']);
        $resData['success'] = 1;
    } else {
        $resData['success'] = 0;
    }

    echo json_encode($resData);
}