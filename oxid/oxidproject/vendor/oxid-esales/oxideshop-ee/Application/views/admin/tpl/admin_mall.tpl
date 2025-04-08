[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function setBox(sBoxId)
{
    oBox = document.getElementById(sBoxId);
    if (oBox != null)
    {   if (oBox.checked)
            oBox.checked = false;
        else
            oBox.checked = true;
    }
}
function checkAll(oBox)
{
    var sCheckBoxes = document.getElementsByName('allartshops[]');
    for(var i=0; i < sCheckBoxes.length; i++) {
        if(sCheckBoxes[i].type == 'checkbox') {
            sCheckBoxes[i].checked = true;
        }
    }
}
function uncheckAll(oBox)
{
    var sCheckBoxes = document.getElementsByName('allartshops[]');
    for(var i=0; i < sCheckBoxes.length; i++) {
        if(sCheckBoxes[i].type == 'checkbox') {
            sCheckBoxes[i].checked = false;
        }
    }
}
window.onload = function ()
{
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]

    oImg = document.getElementById('scrdivheight');
    if (oImg != null && oImg.height)
    {   iCnt = 0;
        oTarget = document.getElementById('scdiv'+ iCnt);
        while (oTarget != null)
        {   iCnt++;
            oTarget.style.height = (parseInt(oImg.height) - 90) + "px";

            oTarget = document.getElementById('scdiv'+ iCnt);
        }
    }
    top.reloadEditFrame();
}
//-->
</script>

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="[{$class}]">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="[{$class}]">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="oxparentid" value="[{ $oxparentid }]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" >
      [{if $allowAssign}]
        <div class="assignmentContainer">
            <div>
                [{ oxmultilang ident="GENERAL_ASSIGNEDTOSUBSHOPS" }]:
            </div>
            [{block name="admin_admin_mall_assignedsubshops"}]
                <div align="right">
                    <a href="#" onClick="checkAll(this)"> [{ oxmultilang ident="ADMIN_MALL_SELECT_ALL_SHOPS" }]</a> /
                    <a href="#" onClick="uncheckAll(this)"> [{ oxmultilang ident="ADMIN_MALL_SELECT_NONE_SHOPS" }]</a>
                </div>
                <div class="mallAssignment">
                    [{assign var="elementCount" value=$shoplist|count}]
                    [{assign var="elementInColumn" value=$elementCount/4|round:0}]
                    [{assign var="ctr" value=0}]
                    <ul class="shopList">
                        [{foreach from=$shoplist item=oShop}]
                            [{if $ctr%($elementInColumn+1) == 0}]
                                </ul>
                                <ul class="shopList">
                            [{/if}]
                            <li><input class="edittext" id="scdiv0_[{$ctr}]" type="checkbox" name="allartshops[]" value="[{ $oShop->oxshops__oxid->value }]" [{ if $oShop->selected}]checked[{/if}]>
                            [{ $oShop->oxshops__oxname->value }]([{ $oShop->oxshops__oxid->value }])
                            </li>
                            [{assign var="ctr" value=`$ctr+1`}]
                        [{/foreach}]
                    </ul>
                </div>
            [{/block}]
        </div>
      [{/if}]
    </td>
    <!-- Ende rechte Seite -->

    </tr>
    <tr>
        <td>[{if $allowAssign}]<input type="submit" [{if $oxid=="-1"}]disabled[{/if}] class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='AssignToSubshops'" [{ $readonly }]>[{/if}]</td>
    </tr>
</table>

</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
