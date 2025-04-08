<table class="edittext rrtableabs" style="width:100%;border-bottom:0;border-top:0;border-right:0">
<tr class="head">
  <td colspan="2">[{ oxmultilang ident=$table noerror=true }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_F" }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_R" }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_D" }]</td>
  <td valign="middle">
    <div onclick="JavaScript:openNode( this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode );" class="closebutton">x</div>
  </td>
</tr>
[{foreach from=$objects.$table item=subitem key=field }]

    [{if isset( $aRights.$table.$field  ) }]
      [{ assign var="idx" value=$aRights.$table.$field  }]
    [{else}]
      [{ assign var="idx" value=3 }]
    [{/if}]

    [{if $iParentIdx < $idx }]
      [{ assign var="idx" value=$iParentIdx }]
    [{/if}]


    [{if $iParentIdx != $idx }]
      [{ assign var="blCustomized" value=1 }]
    [{/if}]

<tr>
  <td>
  </td>
  <td class="title">
    [{ oxmultilang ident=$field noerror=true }]
  </td>
  <td>
    <input [{ $readonly }] type="radio" [{if !($iParentIdx & 2)}]disabled[{/if}] name="aFields[[{$table}]][[{ $field }]]" onmousedown="JavaScript:setPermissions( this, '[{$table}]' );" value="3" [{if $idx & 2 }]checked[{/if}]>
  </td>
  <td>
    <input [{ $readonly }] type="radio" [{if !($iParentIdx & 1)}]disabled[{/if}] name="aFields[[{$table}]][[{ $field }]]" onmousedown="JavaScript:setPermissions( this, '[{$table}]' );" value="1" [{if $idx == 1 }]checked[{/if}]>
  </td>
  <td>
    <input [{ $readonly }] type="radio" name="aFields[[{$table}]][[{ $field }]]" onmousedown="JavaScript:setPermissions( this, '[{$table}]' );" value="0" [{if !$idx }]checked[{/if}]>
  </td>
  <td>
  </td>
</tr>
[{/foreach}]
</table>
