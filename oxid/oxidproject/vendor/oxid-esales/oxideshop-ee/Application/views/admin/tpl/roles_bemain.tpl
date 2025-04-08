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
  var oNode  = oTarget.parentNode.getElementsByTagName('table').item(0);
  var blHide = oNode.style.display;

  // enabling/disabling
  oNode.style.display = ( blHide )?'':'none';
  oTarget.parentNode.parentNode.bgColor = ( blHide )?'#99ccff':'';

  // setting position
  var iPos = parseInt( oTarget.parentNode.parentNode.parentNode.parentNode.style.left );
  iPos = iPos?iPos:0;
  oNode.style.left = iPos + 17;

  // seting z-index
  var iIndex = parseInt( oTarget.parentNode.parentNode.parentNode.parentNode.style.zIndex );
  iIndex = iIndex?iPos:1;
  oNode.style.zIndex = iIndex + 17;

  // unmarking and closing others
  for ( var i=1; i<oTarget.parentNode.parentNode.parentNode.rows.length; i++ ) {
      oChild = oTarget.parentNode.parentNode.parentNode.rows.item( i );
      if ( oChild != oTarget.parentNode.parentNode ) {
        oChild.bgColor = '';
        if ( oChild.cells.length ) {
          aTables = oChild.cells[0].getElementsByTagName('table');
          if ( aTables.length ) {
            aTables.item(0).style.display = 'none'
          }
        }
      }
  }
}

// recursivelly marks permission for deeper nodes
function setDeeperPerms( oObj, iPerm )
{
  for ( var i = 1; i < oObj.rows.length; i++ ) {
    var aRadios = document.getElementsByName( 'aFields[' + oObj.rows.item( i ).id + ']' );
    for ( var e = 0; e < aRadios.length; e++ ) {

      // enabling-disabling
      if ( aRadios.item( e ).value > iPerm ) {
        aRadios.item( e ).disabled = true;
      } else {
        aRadios.item( e ).disabled = false;
      }
      if ( aRadios.item( e ).value == iPerm ) {
        aRadios.item( e ).checked = true;
        updateChildrenNodes( aRadios.item( e ) );
      }

    }
  }
}

// marks permission for deeper nodes
function updateChildrenNodes(oObj)
{
    if ( oObj.parentNode.parentNode.cells.length ) {
        var oTarget = oObj.parentNode.parentNode.cells.item(0).getElementsByTagName( 'table' );
        if ( oTarget.length ) {
            setDeeperPerms( oTarget.item(0), oObj.value );
        }
    }
    var oCustBox = document.getElementById(oObj.name+"_cust");
    if (oCustBox) {
        oCustBox.checked = false;
    }
}

function findChildren(oObj) {
    var ret=new Array();
    var oTarget = oObj.parentNode.parentNode.getElementsByTagName( 'input' );
    if (oTarget) {
        for ( var i = 0; i < oTarget.length; i++ ) {
            if (oTarget.item(i).name.match(/^aFields\[.*\]$/) && oTarget.item(i).type=="radio" && oTarget.item(i).checked) {
                ret.push(oTarget.item(i));
            }
        }
    }
    return ret;
}

function findCust(oObj) {
    var oTarget = oObj;
    while (oTarget && oTarget != undefined && (oTarget.tagName == undefined || oTarget.tagName.toUpperCase() != "TR")) {
        oTarget = oTarget.parentNode;
    }
    if (oTarget && oTarget != undefined && oTarget.tagName != undefined && oTarget.tagName.toUpperCase() == "TR" && oTarget.id) {
        return document.getElementById("aFields["+oTarget.id+"]_cust");
    }
    return null;
}

function findParentRow(oObj) {
    var oTarget = oObj;
    while (oTarget && oTarget != undefined && oTarget.tagName != undefined && oTarget.tagName.toUpperCase() != "TBODY") {
        oTarget = oTarget.parentNode;
    }
    if (oTarget && oTarget != undefined && oTarget.tagName != undefined && oTarget.tagName.toUpperCase() == "TBODY") {
        return oTarget.parentNode;
    }
    return null;
}

function updateCustInfo(oObj) {
    var oSet = findCust(oObj);
    if (oSet) {
        var oChildren = findChildren(oSet);
        if (oChildren.length) {
            var toSet = false;
            var iIdx = oChildren[0].value;
            for ( var i = 1; i < oChildren.length; i++ ) {
                if (iIdx != oChildren[i].value) {
                    toSet = true;
                    break;
                }
            }
            oSet.checked = toSet;
        }
    }
}

// update parents "custom" state
function updateParentNodes(oObj) {
    var oParent = findParentRow(oObj);
    updateCustInfo(oObj);
    if (oParent) {
        updateParentNodes(oParent);
    }
}

// radio onclick handler
function setPerms( oObj )
{
    updateChildrenNodes(oObj);
    updateParentNodes(oObj);
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

.rrtableabs {
    position: absolute;
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
      [{block name="admin_roles_bemain_form"}]
          <tr>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_ACTIVE" }]</td>
            <td>
                <input class="edittext" type="checkbox" name="editval[oxroles__oxactive]" value="1" [{if $edit->oxroles__oxactive->value}]checked[{/if}] [{ $readonly }]>
                [{ oxinputhelp ident="HELP_ROLES_BEMAIN_ACTIVE" }]
            </td>
          </tr>
          <tr>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_TITLE" }]</td>
            <td>
                <input class="edittext" type="text" style="width:215px" name="editval[oxroles__oxtitle]" maxlength="[{$edit->oxroles__oxtitle->fldmax_length}]" value="[{$edit->oxroles__oxtitle->value}]" [{ $readonly }]>
                [{ oxinputhelp ident="HELP_ROLES_BEMAIN_TITLE" }]
            </td>
          </tr>
          <tr>
            <td colspan="2">

        <div style="position:relative">

        <table class="edittext rrtable">
          <tr class="head">
            <td colspan="2">[{ oxmultilang ident="ROLES_BEMAIN_UIROOTHEADER" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_F" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_R" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_D" }]</td>
            <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_CUST" }]</td>
          </tr>

          [{foreach from=$adminmenu item=oNode }]
            [{if $oNode->tagName != 'BTN' }]
                [{ assign var="id" value=$oNode->getAttribute('id') }]
                [{if isset( $aRights.$id ) }]
                  [{ assign var="idx" value=$aRights.$id }]
                [{else}]
                  [{ assign var="idx" value=2 }]
                [{/if}]
                [{if $oNode->hasAttribute('idx') && $oNode->getAttribute('idx') < $idx }]
                  [{ assign var="idx" value=$oNode->getAttribute('idx') }]
                [{/if}]

                <tr id="[{ $oNode->getAttribute('id') }]">
                  <td>
                    [{if $oNode->childNodes->length }]
                      [{ include file="roles_bemain_inc.tpl" aNodes=$oNode->childNodes oParent=$oNode iParentIdx=$idx }]
                      <a href="#" onclick="JavaScript:openNode( this );return false;"> &raquo; </a>
                    [{/if}]
                  </td>
                  <td class="title">
                    [{ oxmultilang ident=$oNode->getAttribute('id') noerror=true }]
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" [{if $oNode->hasAttribute('idx') && $oNode->getAttribute('idx') < 2}]disabled[{/if}] name="aFields[[{ $oNode->getAttribute('id') }]]" onclick="JavaScript:setPerms( this );" value="2" [{if $idx == 2 }]checked[{/if}]>
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" [{if $oNode->hasAttribute('idx') && $oNode->getAttribute('idx') < 1}]disabled[{/if}] name="aFields[[{ $oNode->getAttribute('id') }]]" onclick="JavaScript:setPerms( this );" value="1" [{if $idx == 1 }]checked[{/if}]>
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
                </tr>
            [{/if}]
          [{/foreach}]

          [{* service area *}]

          [{if isset( $aDynRights.dyn_menu ) && $aDynRights.dyn_menu || !isset( $aDynRights.dyn_menu ) }]
            [{if isset( $aRights.dyn_menu ) }]
              [{ assign var="iParentIdx" value=$aRights.dyn_menu }]
            [{else}]
              [{ assign var="iParentIdx" value=2 }]
            [{/if}]
            [{if isset( $aDynRights.dyn_menu ) && $aDynRights.dyn_menu < $iParentIdx }]
              [{ assign var="iParentIdx" value=$aDynRights.dyn_menu }]
            [{/if}]

            <tr id="dyn_menu">
              <td>

                <table class="edittext rrtableabs" style="display:none">
                <tr class="head">
                  <td colspan="2">[{ oxmultilang ident='dyn_menu' noerror=true }]</td>
                  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_F" }]</td>
                  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_R" }]</td>
                  <td>[{ oxmultilang ident="ROLES_BEMAIN_UIRIGHT_D" }]</td>
                  <td valign="middle">
                    <div onclick="JavaScript:openNode( this.parentNode.parentNode.parentNode.parentNode );" class="closebutton">x</div>
                  </td>
                </tr>

                [{if isset( $aRights.dyn_about ) }]
                  [{ assign var="idx" value=$aRights.dyn_about }]
                [{else}]
                  [{ assign var="idx" value=2 }]
                [{/if}]

                [{if isset( $aDynRights.dyn_about ) && $aDynRights.dyn_about < $idx }]
                  [{ assign var="idx" value=$aDynRights.dyn_menu }]
                [{elseif $iParentIdx < $idx }]
                  [{ assign var="idx" value=$iParentIdx }]
                [{/if}]

                <tr id="dyn_about">
                  <td>
                  </td>
                  <td class="title">
                    [{ oxmultilang ident='dyn_about' noerror=true }]
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" [{if $iParentIdx < 2}]disabled[{/if}] name="aFields[dyn_about]" onclick="JavaScript:setPerms( this );" value="2" [{if $idx == 2 }]checked[{/if}]>
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" [{if $iParentIdx < 1}]disabled[{/if}] name="aFields[dyn_about]" onclick="JavaScript:setPerms( this );" value="1" [{if $idx == 1 }]checked[{/if}]>
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" name="aFields[dyn_about]" onclick="JavaScript:setPerms( this );" value="0" [{if !$idx }]checked[{/if}]>
                  </td>
                  <td>
                  </td>
                </tr>

                [{if isset( $aRights.dyn_interface ) }]
                  [{ assign var="idx" value=$aRights.dyn_interface }]
                [{else}]
                  [{ assign var="idx" value=2 }]
                [{/if}]

                [{if isset( $aDynRights.dyn_interface ) && $aDynRights.dyn_interface < $idx }]
                  [{ assign var="idx" value=$aDynRights.dyn_menu }]
                [{elseif $iParentIdx < $idx }]
                  [{ assign var="idx" value=$iParentIdx }]
                [{/if}]

                <tr id="dyn_interface">
                  <td>
                  </td>
                  <td class="title">
                    [{ oxmultilang ident='dyn_interface' noerror=true }]
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" [{if $iParentIdx < 2}]disabled[{/if}] name="aFields[dyn_interface]" onclick="JavaScript:setPerms( this );" value="2" [{if $idx == 2 }]checked[{/if}]>
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" [{if $iParentIdx < 1}]disabled[{/if}] name="aFields[dyn_interface]" onclick="JavaScript:setPerms( this );" value="1" [{if $idx == 1 }]checked[{/if}]>
                  </td>
                  <td>
                    <input [{ $readonly }] type="radio" name="aFields[dyn_interface]" onclick="JavaScript:setPerms( this );" value="0" [{if !$idx }]checked[{/if}]>
                  </td>
                  <td>
                  </td>
                </tr>
                </table>

                <a href="#" onclick="JavaScript:openNode( this );return false;"> &raquo; </a>
              </td>
              <td class="title">
                [{ oxmultilang ident='dyn_menu' noerror=true }]
              </td>
              <td>
                <input [{ $readonly }] type="radio" [{if isset( $aDynRights.dyn_menu ) && $aDynRights.dyn_menu < 2}]disabled[{/if}] name="aFields[dyn_menu]" onclick="JavaScript:setPerms( this );" value="2" [{if $iParentIdx == 2 }]checked[{/if}]>
              </td>
              <td>
                <input [{ $readonly }] type="radio" [{if isset( $aDynRights.dyn_menu ) && $aDynRights.dyn_menu < 1}]disabled[{/if}] name="aFields[dyn_menu]" onclick="JavaScript:setPerms( this );" value="1" [{if $iParentIdx == 1 }]checked[{/if}]>
              </td>
              <td>
                <input [{ $readonly }] type="radio" name="aFields[dyn_menu]" onclick="JavaScript:setPerms( this );" value="0" [{if !$iParentIdx }]checked[{/if}]>
              </td>
              <td>
                <input readonly disabled type="checkbox" id="aFields[dyn_menu]_cust" value="0">
                <script type="text/javascript">
                <!--
                    updateCustInfo(document.getElementById("aFields[dyn_menu]_cust"));
                //-->
                </script>
              </td>
            </tr>
          [{/if}]
          [{* service area *}]

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
