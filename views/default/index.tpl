
{* шаблон главной страницы *}

{foreach $rsProducts as $item name=product}
    <div style="float: left; padding: 0px 30px 40px 0px;">
        <a href="../www/product/{$item['id']}/">
             <img src="../www/images/products/{$item['image']}" width="100"/>
        </a><br>
        <a href="../www/product/{$item['id']}/">{$item['name']}</a>
    </div>
    {if $smarty.foreach.product.iteration mod 3 == 0}
        <div style="clear: both;"></div>
    {/if}
{/foreach}