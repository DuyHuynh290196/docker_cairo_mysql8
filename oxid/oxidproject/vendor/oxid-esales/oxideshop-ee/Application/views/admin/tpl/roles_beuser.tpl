[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

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
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
</form>


<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top">

      <input [{ $readonly }] type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNGROUPS" }]" class="edittext" onclick="JavaScript:showDialog('&cl=roles_beuser&aoc=1&oxid=[{ $oxid }]');">

    </td>
    <td valign="top" class="edittext" width="50%">
      <input [{ $readonly }] type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNUSERS" }]" class="edittext" onclick="JavaScript:showDialog('&cl=roles_beuser&aoc=2&oxid=[{ $oxid }]');">
    </td>
    </tr>
</table>

</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
