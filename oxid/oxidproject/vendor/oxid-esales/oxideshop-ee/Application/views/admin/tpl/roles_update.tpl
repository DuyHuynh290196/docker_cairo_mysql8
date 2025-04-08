[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if isset($refresh) && $nextaction}]
<META HTTP-EQUIV="refresh" CONTENT="[{$refresh}]; URL=[{$oViewConf->getSelfLink()|replace:"&amp;":"&"}]&cl=[{$oViewConf->getActiveClassName()}]&fnc=[{$nextaction}]">
[{/if}]

<br>
<div align="center">
<table cellspacing="0" cellpadding="0" border="0" width="90%">
<tr><td class="edittext" valign="top">
[{if $action}]
<b>Action:</b> [{ $action }][{if isset($progress) }] - [{ $progress }]% complete[{/if}]<br>
[{/if}]
</td>
</tr>
</div>

[{include file="bottomitem.tpl"}]
