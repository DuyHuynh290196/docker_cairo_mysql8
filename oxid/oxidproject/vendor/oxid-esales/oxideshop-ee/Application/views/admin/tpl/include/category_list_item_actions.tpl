[{if $listitem->blIsDerived && $listitem->oxcategories__oxparentid->value == 'oxrootid' && !$oViewConf->isMultiShop()}]
    <a href="Javascript:top.oxid.admin.unassignThis('[{ $listitem->oxcategories__oxid->value }]');" class="unasign" id="una.[{$_cnt}]" [{include file="help.tpl" helpid=item_unassign}]></a>
[{/if}]
[{if $listitem->oxcategories__oxleft->value + 1 == $listitem->oxcategories__oxright->value && !$listitem->blIsDerived}]
    [{oxhasrights object=$listitem right=$smarty.const.RIGHT_DELETE }]
        <a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->oxcategories__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
    [{/oxhasrights}]
[{/if}]