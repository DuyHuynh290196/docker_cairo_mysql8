[{if !$readonly}]
    [{if $listitem->blIsDerived && !$oViewConf->isMultiShop()}]
        <a href="Javascript:top.oxid.admin.unassignThis('[{$listitem->oxlinks__oxid->value}]');" class="unasign" id="una.[{$_cnt}]" [{include file="help.tpl" helpid=item_unassign}]></a>
    [{/if}]
    [{if !$readonly && !$listitem->blIsDerived}]
        <a href="Javascript:top.oxid.admin.deleteThis('[{$listitem->oxlinks__oxid->value}]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
    [{/if}]
[{/if}]