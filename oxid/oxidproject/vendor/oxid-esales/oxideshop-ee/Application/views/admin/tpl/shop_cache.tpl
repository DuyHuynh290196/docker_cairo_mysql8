[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
<script type="text/javascript">
<!--
function _groupExp(el) {
    var _cur = el.parentNode;

    if (_cur.className == "exp") _cur.className = "";
      else _cur.className = "exp";
}
function loadLang(obj)
{
    var langvar = document.getElementById("agblang");
    if (langvar != null )
        langvar.value = obj.value;
    document.myedit.submit();
}
function editThis(sID)
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = '';
    oTransfer.cl.value = top.oxid.admin.getClass( sID );

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.updatenav.value = 1;
    oSearch.submit();
}
function chageCacheConnector(oSelect)
{
    var sConnector, oConnector;
    for (var i=0; i<oSelect.length; i++){
        sConnector = oSelect.options[i].value;
        oConnector = document.getElementById('_'+sConnector);
        if (oConnector) {
            if (sConnector == oSelect.value ) {
                oConnector.className = 'rowexp';
            } else {
                oConnector.className = 'rowhide';
            }
        }
    }
}
function enableCache()
{
    blCheck = true;
    var oForm = document.getElementById("myedit1");
    if( blCheck == true)
    {
        oForm.fnc.value='save';
        oForm.submit();
    }
}
//-->
</script>

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="shop_cache">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>
        <form name="myedit1" id="myedit1" action="[{$oViewConf->getSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="shop_cache">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="voxid" value="[{$oxid}]">
        <input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">

[{include file="include/update_views_notice.tpl"}]
[{block name="admin_shop_cache_form"}]
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="SHOP_CACHE_GROUP_DEFAULT_BACKEND"}]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blCacheActive] value=false>
                    <input type=checkbox name=confbools[blCacheActive] value=true  [{if ($confbools.blCacheActive)}]checked[{/if}] [{$readonly}]>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_DEFAULT_BACKEND_ACTIVE"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_DEFAULT_BACKEND_ACTIVE"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="confinput" style="width:70" name=confstrs[iDefaultCacheTTL] value="[{$confstrs.iDefaultCacheTTL}]" [{$readonly}]>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_DEFAULT_BACKEND_TTL"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_DEFAULT_BACKEND_TTL"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sDefaultCacheConnector]" style="width:95%" onChange="chageCacheConnector(this)" [{$readonly}]>
                        [{if !$confstrs.sDefaultCacheConnector}]
                            <option value="" [{$readonly}]>[{oxmultilang ident="SHOP_SYSTEM_PLEASE_CHOOSE"}]</option>
                        [{/if}]
                        [{foreach from=$aCacheConnectors item=sConnector}]
                            <option value="[{$sConnector|escape}]" [{if $confstrs.sDefaultCacheConnector == $sConnector}]selected[{/if}] [{$readonly}] >[{oxmultilang ident="SHOP_CACHE_CONNECTOR_$sConnector"}]</option>
                        [{/foreach}]
                    </select>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_DEFAULT_BACKEND_CONNECTOR"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_DEFAULT_BACKEND_CONNECTOR"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl id="_oxMemcachedCacheConnector" class="[{if $confstrs.sDefaultCacheConnector == 'oxMemcachedCacheConnector'}]rowexp[{else}]rowhide[{/if}]">
                <dt>
                    <textarea class="confinput" style="width: 270; height: 78" name=confarrs[aMemcachedServers] [{$readonly}]>[{if $confarrs.aMemcachedServers}][{$confarrs.aMemcachedServers}][{else}]localhost@11211@100[{/if}]</textarea>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_DEFAULT_MEMCACHED_SERVERS"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_DEFAULT_MEMCACHED_SERVERS"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl id="_oxFileCacheConnector" class="[{if $confstrs.sDefaultCacheConnector == 'oxFileCacheConnector'}]rowexp[{else}]rowhide[{/if}]">
                <dt>
                    <input type=text class="confinput" style="width:70" name=confstrs[sCacheDir] value="[{if $confstrs.sCacheDir}][{$confstrs.sCacheDir}][{else}]cache[{/if}]" [{$readonly}]>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_DEFAULT_CACHE_DIR"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_DEFAULT_CACHE_DIR"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="SHOP_CACHE_DEFAULT_BACKEND_FLUSH"}]" onClick="Javascript:document.myedit1.fnc.value='flushDefaultCacheBackend'" [{$readonly}]>
                </dt>
                <dd>
                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{oxmultilang ident="SHOP_CACHE_GROUP_CONTENT_CACHE"}]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blUseContentCaching] value=false>
                    <input type=checkbox name=confbools[blUseContentCaching] value=true  [{if ($confbools.blUseContentCaching)}]checked[{/if}] [{$readonly}]>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_ENABLED"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_ENABLED"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="confinput" style="width:70" name=confstrs[iCacheLifeTime] value="[{$confstrs.iCacheLifeTime}]" [{$readonly}]>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_LIFETIME"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_LIFETIME"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="confinput" style="width: 270; height: 78" name=confarrs[aCachableClasses] [{$readonly}]>[{$confarrs.aCachableClasses}]</textarea>
                    [{oxinputhelp ident="HELP_SHOP_CACHE_CLASSES"}]
                </dt>
                <dd>
                    [{oxmultilang ident="SHOP_CACHE_CLASSES"}]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <table border style="border-collapse:collapse;" CELLPADDING=2>
                    <colgroup><col width="55%"><col span=3 width="15%"></colgroup>
                    [{block name="admin_shop_cache_stats"}]
                        <tr>
                           <th class="edittext" colspan="5"><b>[{oxmultilang ident="SHOP_CACHE_TITLE"}]</b></th>
                         </tr>
                         <tr>
                           <td class="edittext">[{oxmultilang ident="SHOP_CACHE_BACKEND"}] </td>
                           <td class="edittext" colspan="3">
                             <select name="confstrs[sCacheBackend]" style="width:95%" [{$readonly}]>
                               [{foreach from=$aCacheBackends key=k item=v}]
                                 <option [{$readonly}] value="[{$k|escape}]" [{if $v}]selected[{/if}]>[{oxmultilang ident="SHOP_CACHE_BACKEND_$k"}]</option>
                               [{/foreach}]
                             </select>
                           </td>
                         </tr>
                         <tr>
                           <td class="edittext">[{oxmultilang ident="SHOP_CACHE_LIFETIME"}] </td>
                           <td class="edittext" colspan="3"><b>[{$ActiveCacheLifetime}]</b> s</td>
                         </tr>
                         [{if !($edit->oxshops__oxproductive->value)}]
                           <tr>
                             <td class="edittext">[{oxmultilang ident="SHOP_CACHE_HIT_STATS"}] </td>
                             <td class="edittext"><b>[{$TotalValidCacheHitCount}]</b></td>
                             <td class="edittext"><b>[{$TotalValidCacheHitRatio}]</b></td>
                             <td class="edittext"><b>[{$TotalValidCacheHitPercent}]</b> %</td>
                           </tr>

                           <tr>
                             <td class="edittext">[{oxmultilang ident="SHOP_CACHE_MISS_STATS"}] </td>
                             <td class="edittext"><b>[{$TotalValidCacheMissCount}]</b></td>
                             <td class="edittext"><b>[{$TotalValidCacheMissRatio}]</b></td>
                             <td class="edittext"><b>[{$TotalValidCacheMissPercent}]</b> %</td>
                           </tr>
                         [{else}]
                           <tr>
                             <td class="edittext">[{oxmultilang ident="SHOP_CACHE_HIT_STATS"}] </td>
                             <td class="edittext" rowspan="2" colspan="3">[{oxmultilang ident="SHOP_CACHE_AVAILABLE_FOR_NON_PRODUCTIVE"}]</td>
                           </tr>

                           <tr>
                             <td class="edittext">[{oxmultilang ident="SHOP_CACHE_MISS_STATS"}] </td>
                           </tr>
                         [{/if}]

                         <tr>
                           <td class="edittext">[{oxmultilang ident="SHOP_CACHE_COUNT_STATS"}] </td>
                           <td class="edittext"><b>[{$TotalValidCacheCount}]</b> </td>
                           <td class="edittext"><b>[{$TotalExpiredCacheCount}]</b></td>
                           <td class="edittext"><b>[{$TotalCacheCount}]</b></td>
                         </tr>


                         <tr>
                           <td class="edittext">[{oxmultilang ident="SHOP_CACHE_SIZE_STATS"}] </td>
                           <td class="edittext"><b>[{math equation="b /1024" b=$TotalValidCacheSize format="%.2f"}]</b>  KB</td>
                           <td class="edittext"><b>[{math equation="b /1024" b=$TotalExpiredCacheSize format="%.2f"}]</b> KB</td>
                           <td class="edittext"><b>[{math equation="b /1024" b=$TotalCacheSize format="%.2f"}]</b> KB</td>
                         </tr>
                    [{/block}]
                  </table>
                </dt>
                <dd>
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type="submit" class="edittext" name="reset" value="[{oxmultilang ident="SHOP_CACHE_CONTENT_CACHE_FLUSH"}]" onClick="Javascript:document.myedit1.fnc.value='flushContentCache'"[{$readonly}]>
                </dt>
                <dd>
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

[{/block}]

    <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit1.fnc.value='save'" [{$readonly}]>

    <input type="submit" class="edittext" name="reset" value="[{oxmultilang ident="SHOP_CACHE_FLUSH"}]" onClick="Javascript:document.myedit1.fnc.value='flushCache'"[{$readonly}]>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
