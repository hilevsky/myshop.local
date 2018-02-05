<?php
/**
 * Created 30.01.2018 23:43 by E. Hilevsky
 */

/**
 * ProductController.php
 *
 * Контроллер станицы товара (/product/1)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/**
 * Формирование страницы товара
 *
 * @param object $smarty шаблонизатор
 */

function indexAction($smarty){
    $itemId = isset($_GET['id']) ? $_GET['id'] : null;
    if($itemId == 0)
        exit;

    // получаем данные товара из БД
    $rsProduct = getProductById($itemId);

    // получаем все категории для формирования левого меню на странице конкретного товара
    $rsCategories = getAllMainCatsWithChildren();

    //Проверяем, есть ли товар в корзине.
    // itemInCart используем для скрытия ссылок "Добавить в корзину", "Удалить из корзины"
    //Если itemInCart=1, скрываем "Добавить в корзину"
    $smarty->assign('itemInCart', 0);
    if(in_array($itemId, $_SESSION['cart'])){
        $smarty->assign('itemInCart', 1);
    }

    $smarty->assign('pageTitle', '');
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('rsProduct', $rsProduct);

    loadTemplate($smarty, 'header');
    loadTemplate($smarty, 'product');
    loadTemplate($smarty, 'footer');
}
