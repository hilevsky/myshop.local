/**
 *  Функция добавления товара в корзину
 *
 *  @param integer itemId   -- id товара
 *  @return                 -- в случае успеха обновляем данные корзины на странице
 */
function addToCart(itemId){
   // console.log("is - addToCart()");
    $.ajax({
        type: 'POST',
       // async: false,
        url: "/cart/addtocart/" + itemId + '/',
        dataType: 'json',
        success: function(data){
            if(data['success']){
                $('#cartCntItems').html(data['cntItems']);

                $('#addCart_'+ itemId).hide();
                $('#removeCart_'+ itemId).show();
            }
        }
    })

}