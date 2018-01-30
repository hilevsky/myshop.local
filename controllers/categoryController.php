<?php
/**
 * Created 13.01.2018 21:31 by E. Hilevsky
 */

/**
 * Контроллер страницы категорий (/category/1)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/**
 * Формирование страницы категорий
 *
 * @param object $smarty шаблонизатор
 */

function indexAction ($smarty){
    $catId = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$catId) exit;

    $rsProducts = null;
    $rsChildcats = null;
    $rsCategory = getCatById($catId);

    // Если категория главная, то показываем подкатегории
    // Иначе - показываем товары этой категории

    if ($rsCategory['parent_id'] == 0) {
        $rsChildCats = getChildrenForCat($catId);
    }
        else {
        $rsProducts = getProductsByCat($catId);
        }

    $rsCategories = getAllMainCatsWithChildren();

    $smarty->assign('pageTitle', 'Товары категории '. $rsCategory['name']);

    $smarty->assign('rsCategory', $rsCategory);
    $smarty->assign('rsProducts', $rsProducts);
    $smarty->assign('rsChildCats', $rsChildCats);

    $smarty->assign('rsCategories', $rsCategories);

    loadTemplate($smarty,'header');
    loadTemplate($smarty,'category');
    loadTemplate($smarty,'footer');

}