[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function SetSticker( sStickerId, oObject)
{
    if (oObject.selectedIndex != -1) {
        oSticker = document.getElementById(sStickerId);
        oSticker.style.display = "";
        oSticker.style.backgroundColor = "#FFFFCC";
        oSticker.style.borderWidth = "1px";
        oSticker.style.borderColor = "#000000";
        oSticker.style.borderStyle = "solid";
        oSticker.innerHTML         = oObject.item(oObject.selectedIndex).innerHTML;
    } else {
        oSticker.style.display = "none";
    }
}
function deleteThis( sID)
{
    blCheck = confirm("[{oxmultilang ident="ARTICLE_VARIANT_YOUWANTTODELETE"}]");
    if (blCheck == true) {
        var oSearch = document.getElementById("search");
        oSearch.fnc.value='deletevariant';
        oSearch.voxid.value=sID;
        oSearch.submit();
    }
}
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
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
    <input type="hidden" name="cl" value="article_variant">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="myedit1" id="myedit1" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="article_variant">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="editval[article__oxid]" value="[{$oxid}]">
    <input type="hidden" name="voxid" value="[{$oxid}]">
    <input type="hidden" name="oxparentid" value="[{$oxparentid}]">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

[{assign var="blWhite" value=""}]
[{assign var="listclass" value=listitem$blWhite}]

  <table border="0" >
    <tr>
      <td class="edittext varsell">
      <form name="myedit3" id="myedit3" action="[{$oViewConf->getSelfLink()}]" method="post">
          [{$oViewConf->getHiddenSid()}]
          <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
          <input type="hidden" name="cl" value="article_variant">
          <input type="hidden" name="fnc" value="">
          <input type="hidden" name="oxid" value="[{$oxid}]">
          <input type="hidden" name="editval[article__oxid]" value="[{$oxid}]">
          <input type="hidden" name="voxid" value="[{$oxid}]">
          <input type="hidden" name="oxparentid" value="[{$oxparentid}]">

          <table cellspacing="0" cellpadding="0" border="0">

          [{oxhasrights object=$edit readonly=$readonly}]
          <tr>
            <td class="edittext">
              [{ oxmultilang ident="ARTICLE_VARIANT_SELECTLIST" }]
            </td>
            <td class="edittext"></td>
          </tr>

          <tr>
            <td class="edittext">
              <select name="allsel[]" size="17" multiple class="editinput" style="width:150px;" onchange="JavaScript:SetSticker('_3',this)">
                [{foreach from=$allsel item=pcat}]
                <option value="[{ $pcat->oxselectlist__oxid->value }]" [{ if $pcat->selected}]SELECTED[{/if}]>[{ $pcat->oxselectlist__oxtitle->value }][{if $pcat->oxselectlist__oxident->value}] | [{ $pcat->oxselectlist__oxident->value }][{/if}]</option>
                [{/foreach}]
              </select>
              [{ oxinputhelp ident="HELP_ARTICLE_VARIANT_SELECTLIST" }]
            </td>
            <td class="edittext">
              &nbsp;<a href="Javascript:document.myedit3.fnc.value='addsel';document.myedit3.submit();" [{if $readonly }]onclick="JavaScript:return false;"[{/if}]><b>==></b></a>&nbsp;<br>
            </td>
          </tr>
          [{/oxhasrights}]

          <tr>
            <td class="edittext">
              <br><br><span name="_3" id="_3" style="position:absolute;height:17px;padding-left:4px;padding-right:4px;padding-top:4px;"></span>
            </td>
            <td></td>
            <td class="edittext">
              <br><br><span name="_4" id="_4" style="position:absolute;height:17px;padding-left:4px;padding-right:4px;padding-top:4px;"></span>
            </td>
          </tr>
        </table>
      </form>

      </td>
      <td>&nbsp;</td>
      <td class="edittext" valign="top">
        <form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
        <input type="hidden" name="cl" value="article_variant">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="fnc" value="changename">
        <input type="hidden" name="voxid" value="">

        <b>[{ oxmultilang ident="ARTICLE_VARIANT_VARNAME" }]</b><br>
        [{oxhasrights object=$edit field='oxvarname' readonly=$readonly }]
          <input type="text" class="editinput" size="32" maxlength="[{$edit->oxarticles__oxvarname->fldmax_length}]" name="editval[oxarticles__oxvarname]" value="[{$edit->oxarticles__oxvarname->value}]">
          [{ oxinputhelp ident="HELP_ARTICLE_VARIANT_VARNAME" }]
        [{/oxhasrights}]

        [{oxhasrights object=$edit readonly=$readonly }]
          <input [{if $readonly && !$edit->canUpdateField('oxprice')}][{$readonly}][{/if}] class="edittext" type="submit" value="[{ oxmultilang ident="ARTICLE_VARIANT_ARTSAVE" }]">
        [{/oxhasrights}]

        <br><br>
        <div style="overflow-x:auto;">
        <table cellspacing="0" cellpadding="0" border="0" width="730">
        <tr>
          [{block name="admin_article_variant_listheader"}]
              <td class="listheader first" height="15">[{ oxmultilang ident="ARTICLE_VARIANT_EDIT" }] </td>
              <td class="listheader">[{ oxmultilang ident="ARTICLE_VARIANT_ACTIVE" }] </td>
              <td class="listheader">[{ oxmultilang ident="ARTICLE_VARIANT_CHOICE" }] </td>
              <td class="listheader">[{ oxmultilang ident="ARTICLE_VARIANT_ARTNUM" }]</td>
              <td class="listheader">[{ oxmultilang ident="ARTICLE_VARIANT_PRICE" }] ([{ $oActCur->sign }])</td>
              <td class="listheader">[{ oxmultilang ident="ARTICLE_VARIANT_SORT" }]</td>
              <td class="listheader" colspan="3">[{ oxmultilang ident="ARTICLE_VARIANT_STOCK" }]</td>
          [{/block}]
        </tr>

        [{if $oViewConf->isBuyableParent()}]
        <tr>
          [{block name="admin_article_variant_parent"}]
              <td class="[{ $listclass}]" colspan="2">&nbsp;</td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$edit field='oxvarselect' readonly=$readonly }]
                <input type="text" class="editinput" size="15" maxlength="[{$edit->oxarticles__oxvarselect->fldmax_length}]" name="editval[oxarticles__oxvarselect]" value="[{$edit->oxarticles__oxvarselect->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$edit field='oxartnum' readonly=$readonly }]
                <input type="text" class="editinput" size="10" maxlength="[{$edit->oxarticles__oxartnum->fldmax_length}]" name="editval[oxarticles__oxartnum]" value="[{$edit->oxarticles__oxartnum->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$edit field='oxprice' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$edit->oxarticles__oxprice->fldmax_length}]" name="editval[oxarticles__oxprice]" value="[{$edit->oxarticles__oxprice->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">&nbsp;</td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$edit field='oxstock' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$edit->oxarticles__oxstock->fldmax_length}]" name="editval[oxarticles__oxstock]" value="[{$edit->oxarticles__oxstock->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$edit field='oxstockflag' readonly=$readonly }]
                <select name="editval[oxarticles__oxstockflag]" class="editinput">
                <option value="1" [{ if $edit->oxarticles__oxstockflag->value == 1 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_STANDARD" }]</option>
                <option value="4" [{ if $edit->oxarticles__oxstockflag->value == 4 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_EXTERNALSTOCK" }]</option>
                <option value="2" [{ if $edit->oxarticles__oxstockflag->value == 2 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_OFFLINE" }]</option>
                <option value="3" [{ if $edit->oxarticles__oxstockflag->value == 3 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_NONORDER" }]</option>
                </select>
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$edit readonly=$readonly }]
                <input [{if $readonly && !$edit->canUpdateField('oxprice')}][{$readonly}][{/if}] class="edittext" type="submit" value="[{ oxmultilang ident="ARTICLE_VARIANT_ARTSAVE" }]" [{$readonly}]>
                [{/oxhasrights}]
              </td>
          [{/block}]
        </tr>
        [{/if}]

        </form>

        <form name="myedit2" id="myedit2" action="[{ $oViewConf->getSelfLink() }]" method="post">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="cl" value="article_variant">
        <input type="hidden" name="fnc" value="savevariants">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="parentvarname" value="[{$edit->oxarticles__oxvarname->value}]">
        <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">

        [{foreach from=$mylist item=listitem}]
        [{assign var="_cnt1" value=$_cnt1+1}]
        <tr id="test_variant.[{$_cnt1}]">
          [{block name="admin_article_variant_listitem"}]
              [{assign var="listclass" value=listitem$blWhite }]
              [{assign var="hasvariants" value=true }]
              <td class="[{ $listclass}]">
                <a href="Javascript:editThis('[{ $listitem->oxarticles__oxid->value}]');" class="[{ $listclass}]" [{include file="help.tpl" helpid=editvariant}]><img src="[{$oViewConf->getImageUrl()}]/editvariant.gif" width="15" height="15" alt="" border="0" align="absmiddle"></a>
              </td>
              <td class="[{ $listclass}]" align="center">
                [{oxhasrights object=$listitem field='oxactive' readonly=$readonly }]
                <input type="hidden" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxactive]" value='0' [{if $listitem->oxarticles__oxactive->value == 1}]checked[{/if}]>
                <input class="edittext" type="checkbox" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxactive]" value='1' [{if $listitem->oxarticles__oxactive->value == 1}]checked[{/if}]>
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxvarselect' readonly=$readonly }]
                <input type="text" class="editinput" size="15" maxlength="[{$listitem->oxarticles__oxvarselect->fldmax_length}]" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxvarselect]" value="[{$listitem->oxarticles__oxvarselect->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxartnum' readonly=$readonly }]
                <input type="text" class="editinput" size="10" maxlength="[{$listitem->oxarticles__oxartnum->fldmax_length}]" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxartnum]" value="[{$listitem->oxarticles__oxartnum->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxprice' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$listitem->oxarticles__oxprice->fldmax_length}]" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxprice]" value="[{$listitem->oxarticles__oxprice->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxsort' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$listitem->oxarticles__oxsort->fldmax_length}]" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxsort]" value="[{$listitem->oxarticles__oxsort->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxstock' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$listitem->oxarticles__oxstock->fldmax_length}]" name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxstock]" value="[{$listitem->oxarticles__oxstock->value}]">
                [{/oxhasrights}]
              </td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxstockflag' readonly=$readonly }]
                <select name="editval[[{ $listitem->oxarticles__oxid->value}]][oxarticles__oxstockflag]" class="editinput">
                <option value="1" [{ if $listitem->oxarticles__oxstockflag->value == 1 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_STANDARD" }]</option>
                <option value="4" [{ if $listitem->oxarticles__oxstockflag->value == 4 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_EXTERNALSTOCK" }]</option>
                <option value="2" [{ if $listitem->oxarticles__oxstockflag->value == 2 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_OFFLINE" }]</option>
                <option value="3" [{ if $listitem->oxarticles__oxstockflag->value == 3 }]SELECTED[{/if}]>[{ oxmultilang ident="GENERAL_NONORDER" }]</option>
                </select>
                [{/oxhasrights}]
              </td>

              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem right=$smarty.const.RIGHT_DELETE }]
                  [{if !$readonly}]
                    <a [{$readonly}] href="Javascript:deleteThis('[{ $listitem->oxarticles__oxid->value }]');" class="delete" alt="" [{include file="help.tpl" helpid=item_delete}]></a>
                  [{/if}]
                [{/oxhasrights}]
              </td>
          [{/block}]
        </tr>

        [{if $blWhite == "2"}]
          [{assign var="blWhite" value=""}]
        [{else}]
          [{assign var="blWhite" value="2"}]
        [{/if}]
        [{/foreach}]

        [{if $hasvariants}]
        <tr>
          <td colspan=9 align=right>
            <input [{if $readonly && !$edit->canUpdateField('oxprice')}][{$readonly}][{/if}] class="edittext" type="submit" onClick="document.forms['myedit2'].elements['parentvarname'].value = document.forms['search'].elements['editval[oxarticles__oxvarname]'].value;" value=" [{ oxmultilang ident="ARTICLE_VARIANT_VARSAVE" }]" [{$readonly}]>
            <br><br>
          </td>
        </tr>
        [{/if}]

        </form>

        [{oxhasrights object=$edit right=$smarty.const.RIGHT_INSERT }]
        <tr>

          [{assign var="listclass" value=listitem$blWhite }]

          <form name="myedit4" id="myedit4" action="[{ $oViewConf->getSelfLink() }]" method="post">
          [{ $oViewConf->getHiddenSid() }]
          <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
          <input type="hidden" name="cl" value="article_variant">
          <input type="hidden" name="fnc" value="savevariant">
          <input type="hidden" name="oxid" value="[{ $oxid }]">
          <input type="hidden" name="voxid" value="-1">
          <input type="hidden" name="parentvarname" value="[{$edit->oxarticles__oxvarname->value}]">

          [{block name="admin_article_variant_newitem"}]
              <td class="[{ $listclass}]">&nbsp;</td>
              <td class="[{ $listclass}]">&nbsp;</td>
              <td class="[{ $listclass}]">
                [{oxhasrights object=$listitem field='oxvarselect' readonly=$readonly }]
                <input type="text" class="editinput" size="15" maxlength="[{$listitem->oxarticles__oxvarselect->fldmax_length}]" name="editval[oxarticles__oxvarselect]" value="">
                [{/oxhasrights}]
              </td>
              <td class="[{$listclass}]">
                [{oxhasrights object=$listitem field='oxartnum' readonly=$readonly }]
                <input type="text" class="editinput" size="10" maxlength="[{$listitem->oxarticles__oxartnum->fldmax_length}]" name="editval[oxarticles__oxartnum]" value="">
                [{/oxhasrights}]
              </td>
              <td class="[{$listclass}]">
                [{oxhasrights readonly=$readonly}]
                <input type="text" class="editinput" size="7" maxlength="[{$listitem->oxarticles__oxprice->fldmax_length}]" name="editval[oxarticles__oxprice]" value="">
                [{/oxhasrights}]
              </td>
              <td class="[{$listclass}]">
                [{oxhasrights object=$listitem field='oxsort' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$listitem->oxarticles__oxsort->fldmax_length}]" name="editval[oxarticles__oxsort]" value="">
                [{/oxhasrights}]
              </td>
              <td class="[{$listclass}]">
                [{oxhasrights object=$listitem field='oxstock' readonly=$readonly }]
                <input type="text" class="editinput" size="7" maxlength="[{$listitem->oxarticles__oxstock->fldmax_length}]" name="editval[oxarticles__oxstock]" value="">
                [{/oxhasrights}]
              </td>
              <td class="[{$listclass}]">
                [{oxhasrights object=$listitem field='oxstockflag' readonly=$readonly }]
                <select name="editval[oxarticles__oxstockflag]" class="editinput">
                <option value="1">[{ oxmultilang ident="GENERAL_STANDARD" }]</option>
                <option value="4">[{ oxmultilang ident="GENERAL_EXTERNALSTOCK" }]</option>
                <option value="2">[{ oxmultilang ident="GENERAL_OFFLINE" }]</option>
                <option value="3">[{ oxmultilang ident="GENERAL_NONORDER" }]</option>
                </select>
                [{/oxhasrights}]
              </td>
          [{/block}]
          <td class="[{ $listclass}]">
            <input class="edittext" type="submit" onClick="document.forms['myedit4'].elements['parentvarname'].value = document.forms['search'].elements['editval[oxarticles__oxvarname]'].value;" value="[{ oxmultilang ident="ARTICLE_VARIANT_NEWVAR" }]" [{$readonly}]>
          </td>
          </form>
        </tr>
        [{/oxhasrights}]

        <tr>
          <form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
          <td  colspan=9><br>

            [{ if $oxid != "-1"}]
            <table cellspacing="2" cellpadding="2" border="0" bgcolor="#D3DFEC">
              <tr>
                <td align="left" class="saveinnewlangtext">
                [{ oxmultilang ident="GENERAL_LANGUAGE" }]
                </td>
                <td align="left">
                  <select name="editlanguage" class="saveinnewlanginput" onChange="Javascript:document.myedit.submit();">
                  [{foreach from=$otherlang key=lang item=olang}]
                  <option value="[{ $lang }]" [{ if $olang->selected}]SELECTED[{/if}]>[{ $olang->sLangDesc }]</option>
                  [{/foreach}]
                  [{foreach from=$posslang key=lang item=desc}]
                  <option value="[{ $lang }]" [{ if $editlanguage == $lang}]SELECTED[{/if}]>[{ $desc}]</option>
                  [{/foreach}]
                  </select>
                </td>
              </tr>
            </table>
            [{/if}]

          </td>
        </tr>
      </table>
      </div>

      [{ $oViewConf->getHiddenSid() }]
      <input type="hidden" name="cl" value="article_variant">
      <input type="hidden" name="fnc" value="">
      <input type="hidden" name="oxid" value="[{ $oxid }]">
      <input type="hidden" name="editval[article__oxid]" value="[{ $oxid }]">
      <input type="hidden" name="voxid" value="[{ $oxid }]">
      <input type="hidden" name="oxparentid" value="[{ $oxparentid }]">

      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
          <td class="edittext">&nbsp;</td>
        </tr>
      </table>

      </form>

      </td>
    </tr>
  </table>
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]