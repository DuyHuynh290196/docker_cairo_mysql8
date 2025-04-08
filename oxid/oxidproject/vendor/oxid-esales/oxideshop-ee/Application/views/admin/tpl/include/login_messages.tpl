[{if ($oViewConf->isStagingMode())}]
    <div class="notify">[{oxmultilang ident="LOGIN_STAGINGMODE_NOTIFY"}]</div>
[{/if}]
[{if ($oViewConf->hasDemoKey())}]
    <div class="notify">[{oxmultilang ident="LOGIN_DEMOMODE_NOTIFY"}]</div>
[{/if}]
[{assign var="sShopValidationMessage" value=$oView->getShopValidationMessage()}]
[{if ($sShopValidationMessage)}]
    [{if ($oView->isGracePeriodExpired())}]
        <div class="notify">[{oxmultilang ident="SHOP_LICENSE_ERROR_GRACE_EXPIRED"}]</div>
    [{else}]
        <div class="notify">[{oxmultilang ident="SHOP_LICENSE_ERROR_$sShopValidationMessage"}]</div>
    [{/if}]
[{/if}]