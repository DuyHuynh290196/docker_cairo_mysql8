<table class="edittext rrtableabs" style="display:none">
<tr class="head">
  <td colspan="2">[{ oxmultilang ident=$oParent->getAttribute('id') noerror=true }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_F" }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_R" }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_D" }]</td>
  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_CUST" }]</td>
  <td valign="middle">
    <div onclick="JavaScript:openNode( this.parentNode.parentNode.parentNode.parentNode );" class="closebutton">x</div>
  </td>
</tr>
[{foreach from=$aNodes item=oNode }]
    [{if $oNode->tagName && $oNode->tagName != 'BTN' }]

        [{ assign var="id" value=$oNode->getAttribute('id') }]
        [{if isset( $aRights.$id ) }]
          [{ assign var="idx" value=$aRights.$id }]
        [{else}]
          [{ assign var="idx" value=2 }]
        [{/if}]

        [{if $oNode->hasAttribute('idx') && $oNode->getAttribute('idx') < $idx }]
          [{ assign var="idx" value=$oNode->getAttribute('idx') }]
        [{elseif $iParentIdx < $idx }]
          [{ assign var="idx" value=$iParentIdx }]
        [{/if}]

        <tr id="[{ $oNode->getAttribute('id') }]">
          <td>
            [{if $oNode->childNodes->length }]
              [{ include file="roles_bemain_inc.tpl" aNodes=$oNode->childNodes oParent=$oNode iParentIdx=$idx  }]
              <a href="#" onclick="JavaScript:openNode( this );return false;"> &raquo; </a>
            [{/if}]
          </td>
          <td class="title">
            [{ oxmultilang ident=$oNode->getAttribute('id') noerror=true }]
          </td>
          <td>
            <input [{ $readonly }] type="radio" [{if $iParentIdx < 2}]disabled[{/if}] name="aFields[[{ $oNode->getAttribute('id') }]]" onclick="JavaScript:setPerms( this );" value="2" [{if $idx == 2 }]checked[{/if}]>
          </td>
          <td>
            <input [{ $readonly }] type="radio" [{if $iParentIdx < 1}]disabled[{/if}] name="aFields[[{ $oNode->getAttribute('id') }]]" onclick="JavaScript:setPerms( this );" value="1" [{if $idx == 1 }]checked[{/if}]>
          </td>
          <td>
            <input [{ $readonly }] type="radio" name="aFields[[{ $oNode->getAttribute('id') }]]" onclick="JavaScript:setPerms( this );" value="0" [{if !$idx }]checked[{/if}]>
          </td>
          <td>
            [{if $oNode->childNodes->length }]
              <input readonly disabled type="checkbox" id="aFields[[{ $oNode->getAttribute('id') }]]_cust" value="0">
              <script type="text/javascript">
              <!--
                updateCustInfo(document.getElementById("aFields[[{ $oNode->getAttribute('id') }]]_cust"));
              //-->
              </script>
            [{/if}]
          </td>
          <td>
          </td>
        </tr>
    [{/if}]
[{/foreach}]
</table>
