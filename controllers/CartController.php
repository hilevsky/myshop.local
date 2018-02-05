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
 * Добавление товара в корзину
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

/**
 * Удаление товара из корзины
 *
 * @param integer id GET параметр   -- id удаляемого из корзины товара
 * @return json                     -- информация об операции (успех/неуспех, кол-во товаров в корзине)
 */

function removefromcartAction(){
    $itemId = isset($_GET['id']) ? (int)($_GET['id'] ) : null;
    if(!$itemId)
        exit();

    $resData = array();
    $key = array_search($itemId, $_SESSION['cart']);
    if($key !== false){
        unset($_SESSION['cart'][$key]);
        $resData['success'] = 1;
        $resData['cntItems'] = count($_SESSION['cart']);
    } else {
        $resData['success'] = 0;
    }

    echo json_encode($resData);
}

/**
 * Формирование страницы корзины
 *
 * @link  /cart/    -- адрес страницы корзины
 */
function indexAction($smarty){

    $itemsIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

    $rsCategories = getAllMainCatsWithChildren();
    $rsProducts = getProductsFromArray($itemsIds);

    $smarty->assign('pageTitle','Корзина');
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProducts', $rsProducts);

    loadTemplate($smarty, 'header');
    loadTemplate($smarty, 'cart');
    loadTemplate($smarty, 'footer');
}