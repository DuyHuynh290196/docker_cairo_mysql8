<dl>
    <dt>
        <input type=hidden name=confbools[blUseLDAP] value=false>
        <input type=checkbox name=confbools[blUseLDAP] value=true  [{if ($confbools.blUseLDAP)}]checked[{/if}] [{$readonly}]>
        [{oxinputhelp ident="HELP_SHOP_SYSTEM_LDAP"}]
    </dt>
    <dd>
        [{oxmultilang ident="SHOP_SYSTEM_LDAP"}]
    </dd>
    <div class="spacer"></div>
</dl>