<?php
/**
 * Контроллер админки /admin/
 */
//подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/UsersModel.php';
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';

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