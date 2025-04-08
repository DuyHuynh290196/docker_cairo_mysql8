[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
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
[{if !$oxparentid}]
window.onload = function ()
{
    [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid}]');
    [{/if}]
    var oField = top.oxid.admin.getLockTarget();
    oField.onchange = oField.onkeyup = oField.onmouseout = top.oxid.admin.unlockSave;
}
[{/if}]
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
    <input type="hidden" name="oxidCopy" value="[{$oxid}]">
    <input type="hidden" name="cl" value="article_main">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

    <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" onSubmit="copyLongDesc('oxarticles__oxlongdesc');" style="padding: 0px;margin: 0px;height:0px;">
      [{$oViewConf->getHiddenSid()}]
      <input type="hidden" name="cl" value="article_main">
      <input type="hidden" name="fnc" value="">
      <input type="hidden" name="oxid" value="[{$oxid}]">
      <input type="hidden" name="voxid" value="[{$oxid}]">
      <input type="hidden" name="oxparentid" value="[{$oxparentid}]">
      <input type="hidden" name="editval[oxarticles__oxid]" value="[{$oxid}]">
      <input type="hidden" name="editval[oxarticles__oxlongdesc]" value="">

      <table cellspacing="0" cellpadding="0" border="0" width="98%">
          <tr>
            <td valign="top" class="edittext">

              <table cellspacing="0" cellpadding="0" border="0">
                [{block name="admin_article_main_form"}]
                    [{if $errorsavingatricle}]
                    <tr>
                      <td colspan="2">
                        [{if $errorsavingatricle eq 1}]
                        <div class="errorbox">[{oxmultilang ident="ARTICLE_MAIN_ERRORSAVINGARTICLE"}]</div>
                        [{/if}]
                      </td>
                    </tr>
                    [{/if}]
                    [{block name="admin_article_main_extended_errorbox"}][{/block}]
                    [{if $oxparentid}]
                    <tr>
                      <td class="edittext" width="120">
                        <b>[{oxmultilang ident="ARTICLE_MAIN_VARIANTE"}]</b>
                      </td>
                      <td class="edittext">
                        <a href="Javascript:editThis('[{$parentarticle->oxarticles__oxid->value}]');" class="edittext"><b>[{$parentarticle->oxarticles__oxartnum->value}] [{$parentarticle->oxarticles__oxtitle->value}] [{if !$parentarticle->oxarticles__oxtitle->value}][{$parentarticle->oxarticles__oxvarselect->value}][{/if}]</b></a>
                      </td>
                    </tr>
                    [{/if}]

                  [{if false && $edit->blForeignArticle}]
                    <tr>
                      <td class="edittext" width="120"></td>
                      <td class="edittext">
                        <input [{$readonly}] type="submit" class="edittext" name="save" value="[{oxmultilang ident="ARTICLE_MAIN_ALLOWCUST"}]" onClick="Javascript:document.myedit.fnc.value='cloneArticle';">
                      </td>
                    </tr>
                  [{/if}]

                    [{oxhasrights object=$edit field='oxactive' readonly=$readonly}]
                    <tr>
                      <td class="edittext" width="120">
                        [{oxmultilang ident="ARTICLE_MAIN_ACTIVE"}]
                      </td>
                      <td class="edittext">
                        <input type="hidden" name="editval[oxarticles__oxactive]" value="0">
                        <input class="edittext" type="checkbox" name="editval[oxarticles__oxactive]" value='1' [{if $edit->oxarticles__oxactive->value == 1}]checked[{/if}]>
                        [{oxmultilang ident="ARTICLE_MAIN_HIDDEN"}]&nbsp;&nbsp;&nbsp;
                        <input type="hidden" name="editval[oxarticles__oxhidden]" value="0">
                        <input class="edittext" type="checkbox" name="editval[oxarticles__oxhidden]" value='1' [{if $edit->oxarticles__oxhidden->value == 1}]checked[{/if}] [{$readonly}]>
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_ACTIVE"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{if $blUseTimeCheck}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_ACTIVFROMTILL"}]&nbsp;
                      </td>
                      <td class="edittext">
                        [{oxhasrights object=$edit field='oxactivefrom' readonly=$readonly}]
                        [{oxmultilang ident="ARTICLE_MAIN_ACTIVEFROM"}]&nbsp;<input type="text" class="editinput" size="27" name="editval[oxarticles__oxactivefrom]" value="[{$edit->oxarticles__oxactivefrom|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}]><br>
                        [{/oxhasrights}]
                        [{oxhasrights object=$edit field='oxactiveto' readonly=$readonly}]
                        [{oxmultilang ident="ARTICLE_MAIN_ACTIVETO"}]&nbsp;&nbsp;<input type="text" class="editinput" size="27" name="editval[oxarticles__oxactiveto]" value="[{$edit->oxarticles__oxactiveto|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}]>
                        [{/oxhasrights}]
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_ACTIVFROMTILL"}]
                      </td>
                    </tr>
                    [{/if}]

                    [{oxhasrights object=$edit field='oxtitle' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_TITLE"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="32" maxlength="[{$edit->oxarticles__oxtitle->fldmax_length}]" id="oLockTarget" name="editval[oxarticles__oxtitle]" value="[{$edit->oxarticles__oxtitle->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_TITLE"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxartnum' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_ARTNUM"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="32" maxlength="[{$edit->oxarticles__oxartnum->fldmax_length}]" name="editval[oxarticles__oxartnum]" value="[{$edit->oxarticles__oxartnum->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_TITLE"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxean' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_EAN"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="13" maxlength="[{$edit->oxarticles__oxean->fldmax_length}]" name="editval[oxarticles__oxean]" value="[{$edit->oxarticles__oxean->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_EAN"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxdistean' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_DISTEAN"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="13" maxlength="[{$edit->oxarticles__oxdistean->fldmax_length}]" name="editval[oxarticles__oxdistean]" value="[{$edit->oxarticles__oxdistean->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_DISTEAN"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxmpn' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_MPN"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="13" maxlength="[{$edit->oxarticles__oxmpn->fldmax_length}]" name="editval[oxarticles__oxmpn]" value="[{$edit->oxarticles__oxmpn->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_MPN"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxshortdesc' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_SHORTDESC"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="32" maxlength="[{$edit->oxarticles__oxshortdesc->fldmax_length}]" name="editval[oxarticles__oxshortdesc]" value="[{$edit->oxarticles__oxshortdesc->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_SHORTDESC"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxsearchkeys' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_SEARCHKEYS"}]&nbsp;
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="32" maxlength="[{$edit->oxarticles__oxsearchkeys->fldmax_length}]" name="editval[oxarticles__oxsearchkeys]" value="[{$edit->oxarticles__oxsearchkeys->value}]">
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_SEARCHKEYS"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxsearchkeys' readonly=$readonly}]
                       [{block name="admin_article_main_extended"}][{/block}]
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxvendorid' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_VENDORID"}]
                      </td>
                      <td class="edittext">
                        <select class="editinput" name="editval[oxarticles__oxvendorid]">
                        <option value="" selected>---</option>
                        [{foreach from=$oView->getVendorList() item=oVendor}]
                        <option value="[{$oVendor->oxvendor__oxid->value}]"[{if $edit->oxarticles__oxvendorid->value == $oVendor->oxvendor__oxid->value}] selected[{/if}]>[{$oVendor->oxvendor__oxtitle->value}]</option>
                        [{/foreach}]
                        </select>
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_VENDORID"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    [{oxhasrights object=$edit field='oxmanufacturerid' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_MANUFACTURERID"}]
                      </td>
                      <td class="edittext">
                        <select class="editinput" name="editval[oxarticles__oxmanufacturerid]" [{$readonly}]>
                        <option value="" selected>---</option>
                        [{foreach from=$oView->getManufacturerList() item=oManufacturer}]
                        <option value="[{$oManufacturer->oxmanufacturers__oxid->value}]"[{if $edit->oxarticles__oxmanufacturerid->value == $oManufacturer->oxmanufacturers__oxid->value}] selected[{/if}]>[{$oManufacturer->oxmanufacturers__oxtitle->value}]</option>
                        [{/foreach}]
                        </select>
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_MANUFACTURERID"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                  [{if $edit->isParentNotBuyable()}]
                  <tr>
                    <td colspan="2">
                      <div class="errorbox">[{oxmultilang ident="ARTICLE_MAIN_PARENTNOTBUYABLE"}]</div>
                    </td>
                  </tr>
                  [{/if}]
                    [{oxhasrights object=$edit field='oxprice' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_PRICE"}] ([{$oActCur->sign}])
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="8" maxlength="[{$edit->oxarticles__oxprice->fldmax_length}]" name="editval[oxarticles__oxprice]" value="[{$edit->oxarticles__oxprice->value}]">
                        [{assign var="oPrice" value=$edit->getPrice()}]
                        &nbsp;<em>( [{$oPrice->getBruttoPrice()}] )</em>
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_PRICE"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]

                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_ALDPRICE"}] ([{$oActCur->sign}])
                      </td>
                      <td class="edittext" nowrap>
                        [{oxhasrights object=$edit field='oxpricea' readonly=$readonly}]
                          [{oxmultilang ident="ARTICLE_MAIN_PRICEA"}] <input type="text" class="editinput" size="4" maxlength="[{$edit->oxarticles__oxpricea->fldmax_length}]" name="editval[oxarticles__oxpricea]" value="[{$edit->oxarticles__oxpricea->value}]">
                        [{/oxhasrights}]
                        [{oxhasrights object=$edit field='oxpriceb' readonly=$readonly}]
                          [{oxmultilang ident="ARTICLE_MAIN_PRICEB"}] <input type="text" class="editinput" size="4" maxlength="[{$edit->oxarticles__oxpriceb->fldmax_length}]" name="editval[oxarticles__oxpriceb]" value="[{$edit->oxarticles__oxpriceb->value}]">
                        [{/oxhasrights}]
                        [{oxhasrights object=$edit field='oxpricec' readonly=$readonly}]
                          [{oxmultilang ident="ARTICLE_MAIN_PRICEC"}] <input type="text" class="editinput" size="4" maxlength="[{$edit->oxarticles__oxpricec->fldmax_length}]" name="editval[oxarticles__oxpricec]" value="[{$edit->oxarticles__oxpricec->value}]">
                        [{/oxhasrights}]
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_ALDPRICE"}]
                      </td>
                    </tr>
                    [{oxhasrights object=$edit field='oxvat' readonly=$readonly}]
                    <tr>
                      <td class="edittext">
                        [{oxmultilang ident="ARTICLE_MAIN_VAT"}]
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="10" maxlength="[{$edit->oxarticles__oxvat->fldmax_length}]" name="editval[oxarticles__oxvat]" value="[{$edit->oxarticles__oxvat->value}]" [{include file="help.tpl" helpid=article_vat}]>
                        [{oxinputhelp ident="HELP_ARTICLE_MAIN_VAT"}]
                      </td>
                    </tr>
                    [{/oxhasrights}]
                [{/block}]

                <tr>
                  <td class="edittext" colspan="2"><br><br>
                    [{oxhasrights object=$edit readonly=$readonly}]
                      <input type="submit" class="edittext" id="oLockButton" name="saveArticle" value="[{oxmultilang ident="ARTICLE_MAIN_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'" [{if !$edit->oxarticles__oxtitle->value && !$oxparentid}]disabled[{/if}] [{if $readonly && (!$edit->canUpdateAnyField() || $edit->isParentNotBuyable())}][{$readonly}][{/if}]>
                    [{/oxhasrights}]
                    [{if $oxid!=-1 && !$readonly}]
                      [{oxhasrights object=$edit right=$smarty.const.RIGHT_INSERT}]
                        <input [{$readonly}] type="submit" class="edittext" name="save" value="[{oxmultilang ident="ARTICLE_MAIN_ARTCOPY"}]" onClick="Javascript:document.myedit.fnc.value='copyArticle';">&nbsp;&nbsp;&nbsp;
                      [{/oxhasrights}]
                    [{/if}]
                  </td>
                </tr>

                [{if $oxid == -1}]
                [{oxhasrights object=$edit readonly=$readonly}]
                <tr>
                  <td class="edittext">
                    [{oxmultilang ident="ARTICLE_MAIN_INCATEGORY"}]
                  </td>
                  <td class="edittext">
                    <select name="art_category" class="editinput" onChange="Javascript:top.oxid.admin.changeLstrt()">
                    <option value="-1">[{oxmultilang ident="ARTICLE_MAIN_NONE"}]</option>
                    [{foreach from=$oView->getCategoryList() item=pcat}]
                    <option value="[{$pcat->oxcategories__oxid->value}]">[{$pcat->oxcategories__oxtitle->getRawValue()|oxtruncate:40:"..":true}]</option>
                    [{/foreach}]
                    </select>
                    [{oxinputhelp ident="HELP_ARTICLE_MAIN_INCATEGORY"}]
                  </td>
                </tr>
                [{/oxhasrights}]
                [{/if}]
                <tr>
                  <td class="edittext" colspan="2"><br>
                  [{include file="language_edit.tpl"}]<br>
                  </td>
                </tr>
                [{if $oxid!=-1 && $thisvariantlist}]
                <tr>
                  <td class="edittext">[{oxmultilang ident="ARTICLE_MAIN_GOTO"}]</td>
                  <td class="edittext">
                    [{include file="variantlist.tpl"}]
                  </td>
                </tr>
                [{/if}]
            </table>
          </td>
    <!-- Anfang rechte Seite -->
          <td valign="top" class="edittext" align="left" style="width:100%;padding-left:5px;padding-bottom:10px;">
            [{block name="admin_article_main_editor"}]
              [{include file="include/editor.tpl" checkrights='oxlongdesc'}]
            [{/block}]
          </td>
    <!-- Ende rechte Seite -->
        </tr>
      </table>
    </form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
