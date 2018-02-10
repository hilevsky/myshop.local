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
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';

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

    $resData = [];

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

    $resData = [];
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

/**
 * Формирование страницы заказа
 *
 */
function orderAction($smarty){

    //получаем массив с id товаров в корзине
    $itemsIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;

    //если в корзине пусто, то переадресация в корзину
    if(!$itemsIds){
        redirect('/cart/');
        return;
    }

    //получаем из массива POST количество каждого товара
    $itemsCnt = [];
    foreach($itemsIds as $item){
        $postVar = 'itemCnt_'.$item;        //ключ для массива POST
        //создаем элемент массива для товара из корзины, ключ - ID товара, значение - количество товара
        $itemsCnt[$item] = isset($_POST[$postVar]) ? $_POST[$postVar] : null;
    }
    //получаем список товаров и их свойств из БД по списку их id
    $rsProducts = getProductsFromArray($itemsIds);

    //добавляем каждому товару доп поля:
    //"realPrice" - кол-во товаров * цену товара
    //"cnt" - кол-во заказанного товара
    //&$item - ссылка на кол-во товара, чтобы при ее изменении менялся и элемент в массиве $rsProducts
    $i = 0;

    foreach($rsProducts as &$item){
        $item['cnt'] = isset($itemsCnt[$item['id']]) ? $itemsCnt[$item['id']] : 0;

        if($item['cnt']){
            $item['realPrice'] = $item['cnt'] * $item['price'];
        } else {
            //если товар есть, а кол-во товара =0, то удаляем товар из массива
            unset($rsProducts[$i]);
        }
        $i++;
    }

    if(!$rsProducts){
        echo "Корзина пуста";
        return;
    }

    //полученный массив покупаемых товаров помещаем в сессионную переменную
    $_SESSION['saleCart'] = $rsProducts;

    $rsCategories = getAllMainCatsWithChildren();

    //формируем страницу заказа

    //hideLoginBox - флаг, чтобы прятать блоки логина и регистрации в левом меню
    if(!isset($_SESSION['user'])) {
        $smarty->assign('hideLoginBox', 1);
        }


    $smarty->assign('pageTitle', 'Заказ');
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProducts', $rsProducts);

    loadTemplate($smarty, 'header');
    loadTemplate($smarty, 'order');
    loadTemplate($smarty, 'footer');

}

/**
 * AJAX-функция сохранение заказа
 *
 * @param array $_SESSION['salecart'] массив покупаемых товаров
 * @return json информация о результате выполнения
 */
function saveOrderAction(){

    //получаем массив покупаемых товаров
    $cart = isset($_SESSION['saleCart']) ? $_SESSION['saleCart'] : null;
    //если корзина пуста, формируем ответ с ошибкой, возвращаем его в формате json и выходим
    if(!$cart){
        $resData['success'] = 0;
        $resData['message'] = "Нет товаров для заказа";
        echo json_encode($resData);
        return;
    }

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    //создаем новый заказ и получаем его id
    $orderId = makeNewOrder($name, $phone, $address);

    //если заказ не создан, то выдаем ошибку и завершаем функцию
    if(!$orderId){
        $resData['success'] = 0;
        $resData['message'] = "Ошибка создания заказа";
        echo json_encode($resData);
        return;
    }

    //сохраняем товары для созданного заказа
    $res = setPurchaseForOrder($orderId, $cart);

    //если успешно, то формируем ответ, очищаем переменные корзины
    if($res){
        $resData['success'] = 1;
        $resData['message'] = "Заказ сохранен";
        unset($_SESSION['saleCart']);
        unset($_SESSION['cart']);
    } else {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка внесения данных для заказа №".$orderId;
    }

    echo json_encode($resData);
}