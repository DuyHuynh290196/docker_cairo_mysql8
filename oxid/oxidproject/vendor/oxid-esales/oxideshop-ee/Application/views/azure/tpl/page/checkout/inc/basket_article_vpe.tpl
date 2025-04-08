[{* packing unit *}]
[{if $basketproduct->oxarticles__oxvpe->value > 1}]
<tr class="notice">
    [{if $editable }]<td></td>[{/if}]
    <td colspan="5">
        [{ oxmultilang ident="ONLY_IN_PACKING_UNITS_OF" }] [{ $basketproduct->oxarticles__oxvpe->value}]
    </td>
    [{if $oView->isWrapping() }]<td></td>[{/if}]
    <td></td>
</tr>
[{/if}]
