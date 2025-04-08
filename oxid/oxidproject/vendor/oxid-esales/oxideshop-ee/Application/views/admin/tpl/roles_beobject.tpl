[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
function openNode( oTarget )
{
  var oNode  = oTarget.parentNode.getElementsByTagName('div').item(0);
  var blHide = oNode.style.display;

  // enabling/disabling
  oNode.style.display = ( blHide )?'':'none';
  oTarget.parentNode.parentNode.bgColor = ( blHide )?'#99ccff':'';

  // setting position
  oNode.style.left = 27;

  // unmarking and closing others
  for ( var i=1; i<oTarget.parentNode.parentNode.parentNode.rows.length; i++ ) {
      var oChild = oTarget.parentNode.parentNode.parentNode.rows.item( i );
      if ( oChild != oTarget.parentNode.parentNode ) {
        oChild.bgColor = '';
        if ( oChild.cells.length ) {
          var aTables = oChild.cells[0].getElementsByTagName('div');
          if ( aTables.length ) {
            aTables.item(0).style.display = 'none'
          }
        }
      }
  }
}

// manages current node and its children permissions
function setPermissions( oObj, sStateObjectId )
{
    var iParentIdx = 0;
    var aInputs = document.getElementsByName( "aFields[" + sStateObjectId + "][" + sStateObjectId + "][]" );
    var iLength = aInputs.length;
    for ( var i = 0; i < iLength; i++ ) {
        if ( aInputs[i].checked && aInputs[i].type == "radio" ) {
            iParentIdx = iParentIdx | aInputs[i].value;
        }
    }

    var iLowestIdx = iParentIdx;
    var aInputs = oObj.parentNode.parentNode.parentNode.getElementsByTagName("input");
    var iLength = aInputs.length;

    for ( var i = 0; i < iLength; i++ ) {
        if ( aInputs[i].name != oObj.name && aInputs[i].checked && iLowestIdx > aInputs[i].value ) {
            iLowestIdx = aInputs[i].value;
            break;
        }
    }

    if ( oObj.value < iLowestIdx ) {
        iLowestIdx = oObj.value;
    }

    // changes custom state marker
    document.getElementById( sStateObjectId + "state" ).checked = (iLowestIdx < iParentIdx) ? true : false;
}

// manages extended parmissions
function setExtendedPermissions( oObj, sStateObjectId )
{
    var aInputs = document.getElementsByName( oObj.name );
    for ( var i = 0; i < aInputs.length; i++ ) {
        if ( aInputs[i].type == 'checkbox' ) {
            aInputs[i].disabled = ( oObj.value < 2 ) ? true : false;
        }
    }

    var aInputs = oObj.parentNode.parentNode.getElementsByTagName("input");
    var iLength = aInputs.length;

    for ( var i = 0; i < iLength; i++ ) {
        if ( aInputs[i].type == "radio" && aInputs[i].name != oObj.name ) {
            if ( aInputs[i].value > oObj.value ) {
                aInputs[i].disabled = true;
            } else {
                aInputs[i].disabled = false;
            }

            if ( aInputs[i].value == oObj.value ) {
                aInputs[i].checked = true;
            }
        }
    }
    document.getElementById( sStateObjectId + "state" ).checked = false;
}
//-->
</script>

<style type="text/css">
<!--
.rrtable, .rrtableabs {
    border-collapse: collapse;
    border:1px solid #000000;
    width:271px;
    border-top:1px solid #000033;
    border-bottom:1px solid #000033;
    background-color: #E7EAED;
}

.rrtable td {
    padding: 3px;
    text-align: center;
}

td.title {
    padding: 3px;
    text-align: left;
}

tr.head td {
    text-align: center;
    background-color: #999999;
    padding: 5px;
}

.closebutton {
    border-top: 1px solid #fff;
    border-left: 1px solid #fff;
    border-bottom: 1px solid #404040;
    border-right: 1px solid #404040;
    text-align: center;
    height: 13px;
    background-color: #d4d0c8;
    cursor: pointer;
    cursor: hand;
    font-weight: bold;
    float: left;
    padding-left: 2px;
    padding-right: 2px;
}
-->
</style>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
</form>

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top">

    <form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="fnc" value="save">
    <input type="hidden" name="oxid" value="[{ $oxid }]">

    <table class="edittext">
      [{block name="admin_roles_beobject_form"}]
          <tr>
            <td colspan="2">

        <div style="position:relative">

        <table class="edittext rrtable">
          <tr class="head">
            <td colspan="2">[{ oxmultilang ident="ROLES_BEOBJECT_OBJECTS" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_X" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_I" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_F" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_R" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_D" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_CUST" }]</td>
          </tr>

          [{foreach from=$objects item=item key=table }]

            [{ assign var="id" value=$table }]
            [{if isset( $aRights.$id.$id ) }]
              [{ assign var="idx" value=$aRights.$id.$id }]
            [{else}]
              [{ assign var="idx" value=15 }]
            [{/if}]

            [{if isset( $aUserRights.$id.$id ) && $aUserRights.$id.$id is not odd by $idx }]
              [{ assign var="idx" value=$aUserRights.$id.$id }]
            [{/if}]

            <tr>
              <td>
                [{if count( $objects.$table ) }]
                  <div style="position:absolute;width:282;height:200;display:none;overflow:auto;border-bottom:1px solid #000033;border-top:1px solid #000033;border-right:1px solid #000033" name="layer">
                  [{ include file="roles_beobject_inc.tpl" table=$table iParentIdx=$idx }]

                    [{ assign var="idxsum" value=0 }]
                    [{ assign var="blCustomized" value=0 }]
                    [{foreach from=$objects.$table item=subitem key=field }]

                        [{if isset( $aRights.$table.$field ) }]
                          [{ assign var="ichilddx" value=$aRights.$table.$field }]
                        [{else}]
                          [{ assign var="ichilddx" value=3 }]
                        [{/if}]

                        [{if $idx < $ichilddx }]
                          [{ assign var="ichilddx" value=$iParentIdx }]
                        [{/if}]

                        [{if ( $idx & 3 ) > $ichilddx }]
                          [{ assign var="blCustomized" value=1 }]
                        [{/if}]

                        [{ assign var="idxsum" value=$idxsum+$ichilddx }]

                    [{/foreach}]


                  </div>
                  <a href="#" onclick="JavaScript:openNode( this );return false;"> &raquo; </a>
                [{/if}]
              </td>
              <td class="title">
                [{ oxmultilang ident=$table noerror=true }]
              </td>
              <td>
                <input [{ $readonly }] type="checkbox" [{if isset( $aRights.$id.$id ) && !($aRights.$id.$id & 2)}]disabled[{/if}] [{if isset( $aUserRights.$id.$id ) && !($aUserRights.$id.$id & 2)}]disabled[{/if}] name="aFields[[{$table}]][[{$table}]][]" value="8" [{if $idx & 8 }]checked[{/if}]>
              </td>
              <td>
                <input [{ $readonly }] type="checkbox" [{if isset( $aRights.$id.$id ) && !($aRights.$id.$id & 2)}]disabled[{/if}] [{if isset( $aUserRights.$id.$id ) && !($aUserRights.$id.$id & 2)}]disabled[{/if}] name="aFields[[{$table}]][[{$table}]][]" value="4" [{if $idx & 4 }]checked[{/if}]>
              </td>
              <td>
                <input [{ $readonly }] type="radio" [{if isset( $aUserRights.$id.$id ) && !($aUserRights.$id.$id & 2) }]disabled[{/if}] name="aFields[[{$table}]][[{$table}]][]" onmousedown="JavaScript:setExtendedPermissions( this, '[{$table}]' );" value="3" [{if $idx & 2 }]checked[{/if}]>
              </td>
              <td>
                <input [{ $readonly }] type="radio" [{if isset( $aUserRights.$id.$id ) && !($aUserRights.$id.$id & 1) }]disabled[{/if}] name="aFields[[{$table}]][[{$table}]][]" onmousedown="JavaScript:setExtendedPermissions( this, '[{$table}]' );" value="1" [{if $idx == 1 }]checked[{/if}]>
              </td>
              <td>
                <input [{ $readonly }] type="radio" name="aFields[[{$table}]][[{$table}]][]" onmousedown="JavaScript:setExtendedPermissions( this, '[{$table}]' );" value="0" [{if !$idx }]checked[{/if}]>
              </td>
              <td>
                [{if count( $objects.$table ) }]
                  <input readonly disabled type="checkbox" id="[{$table}]state" [{if $blCustomized }]checked[{/if}]>
                [{/if}]
              </td>
            </tr>
          [{/foreach}]

        </table>

        </div>

            </td>
          </tr>
          <tr>
            <td colspan="2">
              <br><i>[{ oxmultilang ident="ROLES_BEMAIN_UIINFO" }]</i><br><br>
            </td>
          </tr>
      [{/block}]
      <tr>
        <td colspan="2">
          <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="ROLES_FEMAIN_SAVE" }]" [{ $readonly }]><br>
        </td>
      </tr>
    </table>

    </form>

    </td>
    <!-- Ende rechte Seite -->
    <td>&nbsp;&nbsp;</td>
    <!-- Anfang rechte Seite -->

    <td valign="top" class="edittext">
    </td>
    </tr>
</table>


[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
