[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]
[{assign var="where" value=$oView->getListFilter()}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
}
//-->
</script>

<div id="liste">

<form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{include file="_formparams.tpl" cl="roles_belist" lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang editlanguage=$actlang}]
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup>
    [{block name="admin_roles_belist_colgroup"}]
        <col width="98%"><col width="2%">
    [{/block}]
</colgroup>
<tr class="listitem">
    [{block name="admin_roles_belist_filter"}]
        <td valign="top" class="listfilter first" height="20" colspan="2">
          <div class="r1"><div class="b1">
          <div class="find"><input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]"></div>
          <input class="listedit" type="text" size="50" maxlength="128" name="where[oxroles][oxtitle]" value="[{ $where.oxroles.oxtitle }]">
          </div></div>
        </td>
    [{/block}]
</tr>
<tr>
    [{block name="admin_roles_belist_sorting"}]
        <td class="listheader first" colspan="2" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, 'oxroles', 'oxtitle', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="ROLES_LIST_TITLE" }]</a></td>
    [{/block}]
</tr>

[{assign var="blWhite" value=""}]
[{assign var="_cnt" value=0}]
[{foreach from=$mylist item=listitem}]
    [{assign var="_cnt" value=$_cnt+1}]
    <tr id="row.[{$_cnt}]">
    [{block name="admin_roles_belist_item"}]
        [{ if $listitem->oxorder__oxstorno->value == 1 }]
            [{assign var="listclass" value=listitem3 }]
        [{else}]
            [{ if $listitem->blacklist == 1}]
                [{assign var="listclass" value=listitem3 }]
            [{ else}]
                [{assign var="listclass" value=listitem$blWhite }]
            [{ /if}]
            [{ if $listitem->getId() == $oxid }]
                [{assign var="listclass" value=listitem4 }]
            [{ /if}]
        [{/if}]
        <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxroles__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->oxroles__oxtitle->value }]</a></div></td>
        <td class="[{ $listclass}]">[{if !$readonly}]<a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->oxroles__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>[{/if}]</td>
    [{/block}]
</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
</form>
[{include file="pagenavisnippet.tpl" colspan="2"}]
</table>
</div>

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="mxuadmin" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="mxberoles" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
