[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
function openNode(sNode, sTr, iCnt)
{
    oNode = document.getElementById(sNode + iCnt);
    oTr   = document.getElementById(sTr + iCnt);
    if (oNode == null)
        return;
    if (oNode.style.display == "none")
    {   oNode.style.display = "";
        oTr.bgColor = "#99ccff";
        i = 1;
        while (oTr != null)
        {   if (i != iCnt)
            {    oTr   = document.getElementById(sTr + i);
                 oNode = document.getElementById(sNode + i);
                 if (oTr != null && oTr.bgColor == "#99ccff")
                     oTr.bgColor = "";
                 if (oNode != null)
                     oNode.style.display = "none";
            }
            i++;
        }
    }
    else
    {   oNode.style.display = "none";
        oTr.bgColor = "";
    }
}

function setPerms(oObj, sIdf)
{
    sRight   = oObj.id.match(/^.?.?.?/);
    sPattern = oObj.id.match(/_.*$/);

    if (sRight == sIdf + "d")
    {   blFirst  = true;
        blSecond = true;
    }
    else if (sRight == sIdf + "r")
    {   blFirst  = true;
        blSecond = false;
    }
    else if (sRight == sIdf + "f")
    {   blFirst  = false;
        blSecond = false;
    }
    else
        return;

    iCnt      = 1;
    oDisable1 = document.getElementById(sIdf + "f" + sPattern + "_" + iCnt);
    oDisable2 = document.getElementById(sIdf + "r" + sPattern + "_" + iCnt);

    while (/*oDisable1 != null && */oDisable2 != null)
    {   if (oDisable1 != null)
            oDisable1.disabled = blFirst;
        oDisable2.disabled = blSecond;

        iDeepCnt = 1;
        oDeeper1 = document.getElementById(sIdf + "f" + sPattern + "_" + iCnt + "_" + iDeepCnt);
        oDeeper2 = document.getElementById(sIdf + "r" + sPattern + "_" + iCnt + "_" + iDeepCnt);

        while (oDeeper1 != null && oDeeper2 != null)
        {   oDeeper1.disabled = blFirst;
            oDeeper2.disabled = blSecond;

            iDeepCnt++;
            oDeeper1 = document.getElementById(sIdf + "f" + sPattern + "_" + iCnt + "_" + iDeepCnt);
            oDeeper2 = document.getElementById(sIdf + "r" + sPattern + "_" + iCnt + "_" + iDeepCnt);
        }

        iCnt++;
        oDisable1 = document.getElementById(sIdf + "f" + sPattern + "_" + iCnt);
        oDisable2 = document.getElementById(sIdf + "r" + sPattern + "_" + iCnt);
    }
}
//-->
</script>

<style type="text/css">
.rootnode {
    border: 1px solid #000000;
}
.hidden {
    display: none;
}
</style>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="roles_femain">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxarea" value="[{ $edit->oxroles__oxarea->value }]">
    <input type="hidden" name="oxtype" value="">
    <input type="hidden" name="oxparam" value="">
    <input type="hidden" name="oxparentid" value="">
</form>


<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="roles_femain">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxroles__oxid]" value="[{ $oxid }]">
<input type="hidden" name="editval[oxroles__oxarea]" value="1">

      <table class="edittext" width="250">
        [{block name="admin_roles_femain_form"}]
            <tr>
              <td>[{ oxmultilang ident="ROLES_FEMAIN_ACTIVE" }]</td>
              <td>
                  <input class="edittext" type="checkbox" name="editval[oxroles__oxactive]" value="1" [{if $edit->oxroles__oxactive->value}]checked[{/if}] [{ $readonly }]>
                  [{ oxinputhelp ident="HELP_ROLES_FEMAIN_ACTIVE" }]
              </td>
            </tr>
            <tr>
              <td>[{ oxmultilang ident="ROLES_FEMAIN_TITLE" }]</td>
              <td>
                   <input class="edittext" type="text" style="width:315px" name="editval[oxroles__oxtitle]" maxlength="[{$edit->oxroles__oxtitle->fldmax_length}]" value="[{$edit->oxroles__oxtitle->value}]" [{ $readonly }]>
                   [{ oxinputhelp ident="HELP_ROLES_FEMAIN_TITLE" }]
              </td>
            </tr>
            <tr>
            <td colspan="2">
            <table cellspacing="0" cellpadding="0" border="0" style="width:250px">
            <tr>
                <td class="edittext" id="_rolescontent">
                [{if $oxid != -1 }]

                    <table cellspacing="0" cellpadding="0" style="width:350px;border-top:1px solid #000033;border-bottom:1px solid #000033">
                      <tr>
                        <td>
                        <div style="overflow:auto;height:200;">
                        <table class="edittext" width="100%" cellspacing="0" cellpadding="0">
                          <tr align="center" bgcolor="#999999">
                            <td style="padding:5px">[{ oxmultilang ident="ROLES_FEMAIN_UIHEADER" }]</td>
                            <td style="width:10">[{ oxmultilang ident="ROLES_FEMAIN_UIHEADERRIGHT" }]</td>
                            <td></td>
                          </tr>
                          [{foreach from=$shopfnc item=acurr key=key}]
                          <tr>
                            <td style="padding:5px" nowrap>&nbsp;[{ $acurr->name }] [{if $acurr->params}]<small>([{$acurr->params}])</small>[{/if}]</td>
                            <td align="center">
                            <input name="menuright[[{$key}]][right]" type="hidden" value="0">
                            <input name="menuright[[{$key}]][right]" type="checkbox" value="1" [{if $acurr->value=="1"}]checked[{/if}] [{ $readonly }]>
                            </td>
                            <td>
                              [{if $acurr->deletable }]
                              <a href="#" onclick="Javascript:document.transfer.fnc.value='deleteField';document.transfer.oxparam.value='[{$key}]';document.transfer.submit();" class="delete" [{include file="help.tpl" helpid=item_delete}]></a>
                              [{else}]
                              &nbsp;
                              [{/if}]
                            </td>
                          </tr>
                          [{/foreach}]
                        </table>
                        </div></td>
                      </tr>
                    </table>
                    <input class="edittext" style="width:290" id="newfnc"><input type="button" class="edittext" value="[{ oxmultilang ident="ROLES_FEMAIN_NEWFIELD" }]" style="width:60" onclick="Javascript:document.transfer.fnc.value='addField';document.transfer.oxtype.value=0;document.transfer.oxparam.value=document.getElementById('newfnc').value;document.transfer.oxparentid.value='';document.transfer.submit();" [{$readonly}]>
                    [{ oxmultilang ident="ROLES_FEMAIN_UIEXAMPLE" }]<br>
                    [{ oxmultilang ident="ROLES_FEMAIN_UITPLEXAMPLE" }]<br>
                    <br><i>[{ oxmultilang ident="ROLES_FEMAIN_UIINFO" }]</i>
                [{/if}]
              </td>
            </tr>
        [{/block}]
        <tr>
            <td class="edittext" colspan="2"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="ROLES_FEMAIN_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }]><br>
            </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>


[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
