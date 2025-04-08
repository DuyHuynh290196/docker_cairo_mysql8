[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="category_rights">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">
      [{oxhasrights object=$edit readonly=$readonly right=$smarty.const.RIGHT_VIEW}]
        <input type="button" value="[{oxmultilang ident="CATEGORY_RIGHTS_ASSIGNVISIBLE"}]" class="edittext" onclick="JavaScript:showDialog('&cl=category_rights&aoc=1&oxid=[{$oxid}]');">
      [{/oxhasrights}]
    </td>
    <td valign="top" class="edittext">
      [{oxhasrights object=$edit readonly=$readonly right=$smarty.const.RIGHT_VIEW }]
        <input type="button" value="[{oxmultilang ident="CATEGORY_RIGHTS_ASSIGNBUYABLE"}]" class="edittext" onclick="JavaScript:showDialog('&cl=category_rights&aoc=2&oxid=[{$oxid}]');">
      [{/oxhasrights}]
    </td>
</tr>
</table>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]