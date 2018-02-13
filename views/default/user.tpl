{* Страница пользователя *}

<h1>Ваши регистрационные данные</h1>
<table border="0">
    <tr>
        <td>Логин (email)</td>
        <td>{$arUser['email']}</td>
    </tr>
    <tr>
        <td>Имя</td>
        <td><input type="text" id="newName" value="{$arUser['name']}"></td>
    </tr>
    <tr>
        <td>Телефон</td>
        <td><input type="text" id="newPhone" value="{$arUser['phone']}"></td>
    </tr>
    <tr>
        <td>Адрес</td>
        <td><textarea id="newAddress">{$arUser['address']}</textarea></td>
    </tr>
    <tr>
        <td>Новый пароль</td>
        <td><input type="password" id="newPwd1" value=""></td>
    </tr>
    <tr>
        <td>Повтор пароля</td>
        <td><input type="password" id="newPwd2" value=""></td>
    </tr>
    <tr>
        <td>Чтобы сохранить данные, введите текущий пароль</td>
        <td><input type="password" id="curPwd" value=""></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="button" value="Сохранить изменения" onclick="updateUserData();"></td>
    </tr>

</table>

<h2>Ваши заказы:</h2>
{if !$rsUserOrders}
    Ранее заказов не было
{else}
    <table border="1" cellpadding="1" cellspacing="1">
        <tr>
            <th>№</th>
            <th>Действие</th>
            <th>Номер<br>заказа</th>
            <th>Статус</th>
            <th>Дата<br>создания</th>
            <th>Дата<br>оплаты</th>
            <th>Доп<br>информация</th>
        </tr>
        {foreach $rsUserOrders as $item name=orders}
            <tr>
                <td>{$smarty.foreach.orders.iteration}</td>
                <td><a href="#" onclick="showProducts('{$item['id']}'); return false;">Показать товар заказа</a></td>
                <td>{$item['id']}</td>
                <td>{$item['status']}</td>
                <td>{$item['date_created']}</td>
                <td>{$item['date_payment']}</td>
                <td>{$item['comment']}</td>
            </tr>
            <tr class="hideme" id="purchasesForOrderId_{$item['id']}">
                <td colspan="7">
                    {if $item['children']}
                        <table border="1" cellspacing="1" cellpadding="1" width="100%">
                            <tr>
                                <th>№</th>
                                <th>Артикул</th>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                            </tr>
                            {foreach $item['children'] as $itemChild name=product}
                                <tr>
                                    <td>{$smarty.foreach.product.iteration}</td>
                                    <td>{$itemChild['product_id']}</td>
                                    <td><a href="/product/{$itemChild['product_id']}">{$itemChild['name']}</a></td>
                                    <td>{$itemChild['price']}</td>
                                    <td>{$itemChild['amount']}</td>
                                </tr>
                            {/foreach}
                        </table>
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
{/if}