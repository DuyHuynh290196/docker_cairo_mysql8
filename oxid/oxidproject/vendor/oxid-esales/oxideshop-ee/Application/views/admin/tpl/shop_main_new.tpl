[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function loadLang(obj)
{
    var langvar = document.getElementById("agblang");
    if (langvar != null)
        langvar.value = obj.value;
    document.myedit.submit();
}
function ValidateInfo()
{
    var saveButton = document.myedit.save;
    var shopSelect = document.getElementById("shopparent");
    var checkBox = document.getElementById("isinherited");
    var shopName = document.getElementById("shopname");
    if (saveButton != null && shopSelect != null && checkBox != null) {
        saveButton.disabled = (checkBox.checked && shopSelect.selectedIndex == 0) || shopName.value.length == 0;
    }
}
function editThis(sID)
{
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
    <input type="hidden" name="cl" value="shop_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
    <input type="hidden" name="updatenav" value="">
</form>

[{if $sMandateWarning}]
   <div class="errorbox">[{oxmultilang ident="SHOP_MAIN_MANDATE_WARNING"}]</div>
[{/if}]

[{if $sNewShopWarning}]
   <div class="errorbox">[{oxmultilang ident="SHOP_MAIN_NEWSHOP_WARNING"}]</div>
[{/if}]

[{if $sMaxShopWarning}]
   <div class="errorbox">[{oxmultilang ident="SHOP_MAIN_MAXSHOP_WARNING"}]</div>
[{/if}]

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="shop_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">

    <!-- Anfang rechte Seite -->

    <input type="hidden" name="ismultistore" value=0>

        [{if $oView->isMall() && $malladmin}]
        <table border="0" width="98%">
          [{block name="admin_shop_main_new_form"}]
              <tr>
                <td class="edittext" width=150>
                    [{oxmultilang ident="SHOP_MAIN_ID"}]
                </td>
                <td class="edittext" width=15>&nbsp;</td>

                <td class="edittext">
                    [{$newshopid}]
                </td>
              </tr>
              <script language=JavaScript><!--
                function flipParentSelect()
                {
                  if (document.getElementById('cattree').ismultistore.checked) {
                    document.getElementById('cattree').style.visibility = 'visible';
                  } else {
                    document.getElementById('cattree').style.visibility = 'hidden';
                  }
                }
                //-->
              </script>

             <tr>
                <td class="edittext" >
                            [{oxmultilang ident="SHOP_MAIN_SHOPNAME"}]
                </td>
                <td class="edittext" width=15>&nbsp;</td>
                <td class="edittext">
                  <input type="text" class="editinput" size="35" style="width:200px" maxlength="[{$edit->oxshops__oxname->fldmax_length}]" id="shopname" name="editval[oxshops__oxname]" value="[{$edit->oxshops__oxname->value}]" onchange="JavaScript:ValidateInfo();" onkeyup="JavaScript:ValidateInfo();" onmouseout="JavaScript:ValidateInfo();" [{$readonly}]>
                  [{oxinputhelp ident="HELP_SHOP_MAIN_SHOPNAME"}]
                </td>
             </tr>

             <tr>
                <td class="edittext wrap" >
                            [{oxmultilang ident="SHOP_MAIN_ISINHERITED"}]
                </td>
                <td class="edittext" width=15>&nbsp;</td>
                <td class="edittext">
                <input type="hidden" class="editinput" name="editval[oxshops__oxisinherited]" value=0>
                <input type="checkbox" id="isinherited" name="editval[oxshops__oxisinherited]" [{$readonly}] value=1 onclick="JavaScript:ValidateInfo();">
                [{oxinputhelp ident="HELP_SHOP_MAIN_SHOPNAME"}]
                </td>
             </tr>

             <tr>
                <td class="edittext" >
                    [{oxmultilang ident="SHOP_MAIN_ISSUPERSHOP"}]
                </td>
                <td class="edittext" width=15>&nbsp;</td>
                <td class="edittext">
                <input type="hidden" class="editinput" name="editval[oxshops__oxissupershop]" value=0>
                <input type="checkbox" name="editval[oxshops__oxissupershop]" [{$readonly}] value=1>
                [{oxinputhelp ident="HELP_SHOP_MAIN_SHOPNAME"}]
                </td>
             </tr>

             <tr>
                <td class="edittext" >
                    [{oxmultilang ident="SHOP_MAIN_ISMULTISHOP"}]
                </td>
                <td class="edittext" width=15>&nbsp;</td>
                <td class="edittext">
                <input type="hidden" class="editinput" name="editval[oxshops__oxismultishop]" value=0>
                <input type="checkbox" name="editval[oxshops__oxismultishop]" [{$readonly}] value=1>
                [{oxinputhelp ident="HELP_SHOP_MAIN_SHOPNAME"}]
                </td>
             </tr>

             <tr>
                <td class="edittext wrap" >
                    [{oxmultilang ident="SHOP_MAIN_SHOPPARENT"}]
                </td>
                <td class="edittext" width=15>&nbsp;</td>
                <td class="edittext">
                 <select class="editinput" id="shopparent" name="editval[oxshops__oxparentid]" onclick="JavaScript:ValidateInfo();" onkeyup="JavaScript:ValidateInfo();" [{$readonly}]>
                    <option value="">--</option>
                  [{foreach from=$shopids item=oShop}]
                    <option value="[{$oShop->oxshops__oxid->value}]">[{$oShop->oxshops__oxname->value}] ([{$oShop->oxshops__oxid->value}])</option>
                  [{/foreach}]
                 </select>
                 [{oxinputhelp ident="HELP_SHOP_MAIN_SHOPNAME"}]
                </td>
              </tr>
        [{/block}]
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext" width=15>&nbsp;</td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'"" [{if $oxid==-1}]disabled[{/if}] [{$readonly}]>
            </td>
        </tr>
        </table>
      [{/if}]
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
