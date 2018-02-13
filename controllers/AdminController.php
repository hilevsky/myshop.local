<?php
/**
 * Контроллер админки /admin/
 */
//подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/UsersModel.php';
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';
include_once '../models/ProductsModel.php';

$smarty->setTemplateDir(TemplateAdminPrefix);
$smarty->assign('TemplateWebPath', TemplateAdminWebPath);

function indexAction($smarty){

    $rsCategories = getAllMainCategories();

    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('pageTitle', 'Управление сайтом');

    loadTemplate($smarty, 'adminHeader');
    loadTemplate($smarty, 'admin');
    loadTemplate($smarty, 'adminFooter');
}

/**
 * Добавление новой категории товара
 *
 */
function addnewcatAction(){

    $catName = isset($_POST['newCategoryName']) ? $_POST['newCategoryName'] : null;
    $catParentId = $_POST['generalCatId'];

    if(!$catName){
        $resData['message'] = "Введите название категории";
        $resData['success'] = 0;
        echo json_encode($resData);
        exit;
    }

    $res = insertCat($catName, $catParentId);

    if($res){
        $resData['message'] = "Категория добавлена";
        $resData['success'] = 1;
    } else {
        $resData['message'] = "Ошибка добавления категории";
        $resData['success'] = 0;
    }
    echo json_encode($resData);
    return;
}

/**
 * Страница управления категориями товара
 *
 * @param type $smarty
 *
 */
function categoryAction($smarty){

    $rsCategories = getAllCategories();
    $rsMainCategories = getAllMainCategories();

    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsMainCategories', $rsMainCategories);
    $smarty->assign('pageTitle', 'Управление сайтом');

    loadTemplate($smarty, 'adminHeader');
    loadTemplate($smarty, 'adminCategory');
    loadTemplate($smarty, 'adminFooter');
}
/**
 * Редактирование категорий
 */
function updatecategoryAction(){
    $itemId = $_POST['itemId'];
    $parentId = $_POST['parentId'];
    $newName = $_POST['newName'];

    $res = updateCategoryData($itemId, $parentId, $newName);

    if($res){
        $resData['success'] = 1;
        $resData['message'] = "Категория обновлена";
    } else {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка изменения данных категории";
    }

    echo json_encode($resData);
    return;
}

/**
 * Страница управления товарами
 *
 * @param type $smarty
 *
 */
function productsAction($smarty){

    $rsCategories = getAllCategories();
    $rsProducts = getProducts();

    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProducts', $rsProducts);
    $smarty->assign('pageTitle', 'Управление сайтом');

    loadTemplate($smarty, 'adminHeader');
    loadTemplate($smarty, 'adminProducts');
    loadTemplate($smarty, 'adminFooter');
}

/**
 *  Добавление товара
 */
function addproductAction(){
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemDesc = $_POST['itemDesc'];
    $itemCat = $_POST['itemCatId'];

    $res = insertProduct($itemName, $itemPrice, $itemDesc, $itemCat);

    if($res){
        $resData['success'] = 1;
        $resData['message'] = "Изменения успешно внесены";
    } else {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка изменения данных";
    }

    echo json_encode($resData);
    return;
}

/**
 * Редактирование товара
 */
function updateproductAction(){
    $itemId = $_POST['itemId'];
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemStatus = $_POST['itemStatus'];
    $itemDesc = $_POST['itemDesc'];
    $itemCat = $_POST['itemCatId'];

    $res = updateProduct($itemId, $itemName, $itemPrice, $itemStatus, $itemDesc, $itemCat);

    if($res){
        $resData['success'] = 1;
        $resData['message'] = "Изменения успешно внесены";
    } else {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка изменения данных";
    }

    echo json_encode($resData);
    return;
}

/**
 * Загрузка картинок для товаров
 */
function uploadAction(){

    $maxSize = 2 * 1024 * 1024; //не больше 2 Мб

    $itemId = $_POST['itemId'];

    //получаем расширение загружаемого файла
    $ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);

    //создаем имя файла
    $newFileName = $itemId.'.'.$ext;

    if($_FILES['filename']['size'] > $maxSize){
        echo "Размер файла превышает 2 Мб";
        return;
    }

    //проверяем, загружен ли файл
    if(is_uploaded_file($_FILES['filename']['tmp_name'])){

        //если файл загружен, перемещаем его в конечную папку, присваиваем новое имя
        $res = move_uploaded_file($_FILES['filename']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/images/products/'.$newFileName);
        if($res){
            $res = updateProductImage($itemId, $newFileName);
            if($res){
                redirect('/admin/products/');
            }
        }
    } else {
        echo "Ошибка загрузки файла";
    }
}

/**
 * Страница заказов /admin/orders/
 */
function ordersAction($smarty){

    $rsOrders = getOrders();

    $smarty->assign('rsOrders', $rsOrders);
    $smarty->assign('pageTitle', 'Заказы');

    loadTemplate($smarty, 'adminHeader');
    loadTemplate($smarty, 'adminOrders');
    loadTemplate($smarty, 'adminFooter');
}

/**
 * Обновление статуса заказа в БД (AJAX-запрос со страницы /admin/orders/)
 */
function setorderstatusAction(){
    $itemId = $_POST['itemId'];
    $status = $_POST['status'];

    $res = updateOrderStatus($itemId, $status);

    if($res){
        $resData['success'] = 1;
    } else {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка установки статуса";
    }
    echo json_encode($resData);
    return;
}

/**
 * Сохранение в БД даты оплаты заказа (AJAX-запрос со страницы /admin/orders/)
 */
function setorderdatepaymentAction(){
    $itemId = $_POST['itemId'];
    $datePayment = $_POST['datePayment'];

    $res = updateOrderDatePayment($itemId, $datePayment);

    if($res){
        $resData['success'] = 1;
    } else {
        $resData['success'] = 0;
        $resData['message'] = "Ошибка сохранения";
    }
    echo json_encode($resData);
    return;
}