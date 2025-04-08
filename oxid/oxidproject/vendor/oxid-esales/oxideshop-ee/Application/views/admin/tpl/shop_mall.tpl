[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = '';
    oTransfer.cl.value = top.oxid.admin.getClass( sID );

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.updatenav.value = 1;
    oSearch.submit();
}
//-->
</script>

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="shop_mall">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="myedit1" id="myedit1" action="[{$oViewConf->getSelfLink()}]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="shop_mall">
<input type="hidden" name="fnc" value="save">
<input type="hidden" name="oxid" value="[{$oxid}]">
<input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">

[{include file="include/update_views_notice.tpl"}]

    <table border="0" width="98%">
    [{block name="admin_shop_mall_form"}]
    [{if $oxid != "1"}]
        <tr>
         <td valign="top" class="conftext">
            <input type=text class="confinput" style="width:270" name=confstrs[sMallShopURL] value="[{$confstrs.sMallShopURL}]" [{$readonly}]>
            [{oxinputhelp ident="HELP_SHOP_MALL_SHOPURL"}]
         </td>
         <td valign="top" class="conftext" width="100%">
           [{oxmultilang ident="SHOP_MALL_SHOPURL"}]
         </td>
        </tr>


        <tr>
         <td valign="top" class="conftext2">
            <input type=text class="confinput" style="width:270" name=confstrs[sMallSSLShopURL] value="[{$confstrs.sMallSSLShopURL}]" [{$readonly}]>
            [{oxinputhelp ident="HELP_SHOP_MALL_SHOPSSLSHOPURL"}]
         </td>
         <td valign="top" class="conftext2" width="100%">
           [{oxmultilang ident="SHOP_MALL_SHOPSSLSHOPURL"}]
         </td>
        </tr>

        <tr>
         <td valign="top" class="conftext">
            <input type=hidden name=confbools[blNativeImages] value=false>
            <input type=checkbox name=confbools[blNativeImages] value=true  [{if ($confbools.blNativeImages)}]checked[{/if}] [{$readonly}]>
            [{oxinputhelp ident="HELP_SHOP_MALL_NATIVEIMAGES"}]
         </td>
         <td valign="top" class="conftext" width="100%">
            [{oxmultilang ident="SHOP_MALL_NATIVEIMAGES"}]
         </td>
        </tr>
    [{else}]
        <tr>
         <td valign="top" class="conftext">
            <select name=confstrs[iMallMode] class="confinput" style="width: 270" [{$readonly}]>
                <option value=0 [{if ($confstrs.iMallMode == 0)}]selected[{/if}]>[{oxmultilang ident="SHOP_MALL_NOSTARTSITE"}]
                <option value=1 [{if ($confstrs.iMallMode == 1)}]selected[{/if}]>[{oxmultilang ident="SHOP_MALL_STARTSITE"}]
            </select>
            [{oxinputhelp ident="HELP_SHOP_MALL_MALLMODE"}]
         </td>
         <td valign="top" class="conftext" width="100%">
           [{oxmultilang ident="SHOP_MALL_MALLMODE"}]
         </td>
        </tr>
    [{/if}]
        <tr>
         <td valign="top" class="conftext2">
            <input type=hidden name=confbools[blSeparateNumbering] value=false>
            <input type=checkbox name=confbools[blSeparateNumbering] value=true  [{if ($confbools.blSeparateNumbering)}]checked[{/if}] [{$readonly}]>
            [{oxinputhelp ident="HELP_SHOP_MALL_SEPARATENUMBERING"}]
         </td>
         <td valign="top" class="conftext2" width="100%">
            [{oxmultilang ident="SHOP_MALL_SEPARATENUMBERING"}]
         </td>
        </tr>

        <tr>
         <td valign="top" class="conftext">

            <input type=text class="confinput" style="width:70" name=confstrs[iMallPriceAddition] value="[{if $confstrs.iMallPriceAddition}][{$confstrs.iMallPriceAddition}][{else}]0[{/if}]" [{$readonly}]>
            <select class="confinput" name=confbools[blMallPriceAdditionPercent] [{$readonly}]>
              <option value=true [{if $confbools.blMallPriceAdditionPercent}]selected[{/if}]>%</option>
              <option value=false [{if !$confbools.blMallPriceAdditionPercent}]selected[{/if}]>[{$defCur}]</option>
            </select>
            [{oxinputhelp ident="HELP_SHOP_MALL_PRICEADDITION"}]
         </td>
         <td valign="top" class="conftext" width="100%">
            [{oxmultilang ident="SHOP_MALL_PRICEADDITION"}]
         </td>
        </tr>

        <tr>
         <td valign="top" class="conftext2">
            <input type=hidden name=confbools[blMallCustomPrice] value=false>
            <input type=checkbox name=confbools[blMallCustomPrice] value=true  [{if ($confbools.blMallCustomPrice)}]checked[{/if}] [{$readonly}]>
            [{oxinputhelp ident="HELP_SHOP_MALL_MALLCUSTOMPRICE"}]
         </td>
         <td valign="top" class="conftext2" width="100%">
            [{oxmultilang ident="SHOP_MALL_MALLCUSTOMPRICE"}]
         </td>
        </tr>

     [{if $oxid == "1"}]
        <tr>
         <td valign="top" class="conftext">
            <input type=hidden name=confbools[blMallUsers] value=false>
            <input type=checkbox name=confbools[blMallUsers] value=true  [{if ($confbools.blMallUsers)}]checked[{/if}] [{$readonly}]>
            [{oxinputhelp ident="HELP_SHOP_MALL_MALLUSERS"}]
         </td>
         <td valign="top" class="conftext" width="100%">
            [{oxmultilang ident="SHOP_MALL_MALLUSERS"}]
         </td>
        </tr>
    [{/if}]

        <tr>
         <td valign="top" class="conftext">
         <input type="submit" class="confinput" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit1.fnc.value='save'" [{$readonly}]>
         </td>
         <td valign="top" class="conftext" width="100%">
         </td>
        </tr>
    [{/block}]
    </table>
</form>

[{if $showInheritanceUpdate}]

    <hr>

[{oxmultilang ident="SHOP_MALL_MALLINHERITANCE"}]
<br><br>

<form name="myedit2" id="myedit2" action="[{$oViewConf->getSelfLink()}]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="shop_mall">
<input type="hidden" name="fnc" value="changeInheritance">
<input type="hidden" name="oxid" value="[{$oxid}]">
<input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">

     <table>

        [{block name="admin_shop_mall_inheritance"}]
            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxarticles] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxarticles] value=true  [{if ($confbools.blMallInherit_oxarticles)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXARTICLES"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxattribute] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxattribute] value=true  [{if ($confbools.blMallInherit_oxattribute)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXATTRIBUTES"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxdiscount] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxdiscount] value=true  [{if ($confbools.blMallInherit_oxdiscount)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXDISCOUNT"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxdelivery] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxdelivery] value=true  [{if ($confbools.blMallInherit_oxdelivery)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXDELIVERY"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxlinks] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxlinks] value=true  [{if ($confbools.blMallInherit_oxlinks)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXLINKS"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxvoucherseries] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxvoucherseries] value=true  [{if ($confbools.blMallInherit_oxvoucherseries)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXVOUCHERSERIES"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxnews] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxnews] value=true  [{if ($confbools.blMallInherit_oxnews)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXNEWS"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxselectlist] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxselectlist] value=true  [{if ($confbools.blMallInherit_oxselectlist)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXSELECTLIST"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxvendor] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxvendor] value=true  [{if ($confbools.blMallInherit_oxvendor)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXVENDOR"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxmanufacturers] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxmanufacturers] value=true  [{if ($confbools.blMallInherit_oxmanufacturers)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXMANUFACTURER"}]
             </td>
            </tr>

            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMallInherit_oxwrapping] value=false>
                <input type=checkbox name=confbools[blMallInherit_oxwrapping] value=true  [{if ($confbools.blMallInherit_oxwrapping)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXWRAPPING"}]
             </td>
            </tr>
            <tr>
             <td valign="top" class="conftext" colspan=2>
             <input type="submit" class="confinput" name="save" value="[{oxmultilang ident="SHOP_MALL_SAVE_INHERITANCE"}]" [{$readonly}]>
             </td>
            </tr>
        [{/block}]

     </table>
</form>
    [{/if}]

[{if $oViewConf->isMultiShop()}]
<hr>
<br>
<form name="myedit3" id="myedit3" action="[{$oViewConf->getSelfLink()}]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="shop_mall">
<input type="hidden" name="fnc" value="changeInheritance">
<input type="hidden" name="oxid" value="[{$oxid}]">
<input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">

     <table>
        [{block name="admin_shop_mall_multishop"}]
            <tr>
             <td valign="top" class="conftext">
                <input type=hidden name=confbools[blMultishopInherit_oxcategories] value=false>
                <input type=checkbox name=confbools[blMultishopInherit_oxcategories] value=true  [{if ($confbools.blMultishopInherit_oxcategories)}]checked[{/if}] [{$readonly}]>
             </td>
             <td valign="top" class="conftext" width="100%">
                [{oxmultilang ident="SHOP_MALL_MALLINHERIT_OXCATEGORIES"}]
             </td>
            </tr>
            <tr>
             <td valign="top" class="conftext" colspan=2>
             <input type="submit" class="confinput" name="save" value="[{oxmultilang ident="SHOP_MALL_SAVE_INHERITANCE"}]" [{$readonly}]>
             </td>
            </tr>
        [{/block}]

     </table>
</form>
    [{/if}]

    [{if $showViewUpdate}]
      <hr>
      <form name="regerateviews" id="regerateviews" action="[{$oViewConf->getSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="shop_mall">
        <input type="hidden" name="fnc" value="updateViews">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <br>[{oxmultilang ident="SHOP_MALL_UPDATEVIEWSINFO"}]<br><br>
        <input class="confinput" type="Submit" value="[{oxmultilang ident="SHOP_MALL_UPDATEVIEWSNOW"}]" onClick="return confirm('[{oxmultilang ident="SHOP_MALL_UPDATEVIEWSCONFIRM"}]')" [{$readonly}]>
    [{/if}]

</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
