<input type="hidden" name="ismultistore" value=0><br>

[{if $edit->oxshops__oxismultishop->value}]
    <tr>
        <td class="edittext" colspan=2 style="height:15px">
            <img src="[{$oViewConf->getImageUrl()}]/checkmark.gif" hspace="0" vspace="0" border="0" align="middle" alt="" width="7" height="5">
            [{oxmultilang ident="SHOP_MAIN_ISMULTISHOP"}]
        </td>
    </tr>
[{/if}]

[{if $edit->oxshops__oxissupershop->value}]
    <tr>
        <td class="edittext" colspan=2 style="height:15px">
            <img src="[{$oViewConf->getImageUrl()}]/checkmark.gif" hspace="0" vspace="0" border="0" align="middle" alt="" width="7" height="5">
            [{oxmultilang ident="SHOP_MAIN_ISSUPERSHOP"}]
        </td>
    </tr>
[{/if}]

<tr>
    <td class="edittext" style="height:15px">
        [{oxmultilang ident="SHOP_MAIN_SHOPPARENT"}]
    </td>
    <td class="edittext">
        [{if $edit->oxshops__oxparentid->value}][{$parentName}]([{$edit->oxshops__oxparentid->value}])[{else}]--[{/if}]
    </td>
</tr>

<tr>
    <td class="edittext" style="height:15px">
        [{oxmultilang ident="SHOP_MAIN_ID"}]
    </td>
    <td class="edittext">
        [{$oxid}]
    </td>
</tr>