[{if $listitem->blIsDerived && !$oViewConf->isMultiShop()}]
    <a href="Javascript:top.oxid.admin.unassignThis('[{ $listitem->oxnews__oxid->value }]');" class="unasign" id="una.[{$_cnt}]" [{include file="help.tpl" helpid=item_unassign}]></a>
[{/if}]
[{if !$listitem->blIsDerived}]
    <a href="Javascript:top.oxid.admin.deleteThis('[{$listitem->oxnews__oxid->value}]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
[{/if}]