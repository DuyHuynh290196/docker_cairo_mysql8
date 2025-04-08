[{assign var="basketAmount" value='' }]
[{if isset($oxcmp_basket) && $oxcmp_basket->getItemsCount() > 0}]
    [{assign var="basketAmount" value=$oxcmp_basket->getItemsCount() }]
[{/if}]
<li>
    <a href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=basket"}]" rel="nofollow">
        <i class="fa fa-shopping-cart"></i>&nbsp;<span class="badge" id="navigation-basket-amount">[{$basketAmount}]</span>
    </a>
</li>
