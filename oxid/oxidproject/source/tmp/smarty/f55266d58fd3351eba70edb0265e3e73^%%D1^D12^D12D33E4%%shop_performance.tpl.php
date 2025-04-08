<?php /* Smarty version 2.6.33, created on 2025-04-02 05:10:09
         compiled from shop_performance.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'shop_performance.tpl', 1, false),array('function', 'cycle', 'shop_performance.tpl', 28, false),array('function', 'oxinputhelp', 'shop_performance.tpl', 54, false),array('function', 'oxmultilang', 'shop_performance.tpl', 57, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById("transfer");
    oTransfer.oxid.value = '';
    oTransfer.cl.value = top.oxid.admin.getClass(sID);

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById("search");
    oSearch.oxid.value = sID;
    oSearch.updatenav.value = 1;
    oSearch.submit();
}
//-->
</script>

<?php if ($this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<?php echo smarty_function_cycle(array('assign' => '_clear_','values' => ",2"), $this);?>


<form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="cl" value="shop_performance">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveShopId(); ?>
">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
</form>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="cl" value="shop_performance">
    <input type="hidden" name="fnc" value="save">
    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="editval[oxshops__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include/update_views_notice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <table border=0>
        
            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top">
                <input type=hidden name=confbools[blCheckTemplates] value=false>
                <input type=checkbox name=confbools[blCheckTemplates] value=true  <?php if (( $this->_tpl_vars['confbools']['blCheckTemplates'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_CHECKIFTPLCOMPILE'), $this);?>

             </td>
             <td valign="top" width="100%" >
                <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_CHECKIFTPLCOMPILE'), $this);?>

             </td>
            </tr>

            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top">
                <input type=hidden name=confbools[blLoadVariants] value=false>
                <input type=checkbox name=confbools[blLoadVariants] value=true  <?php if (( $this->_tpl_vars['confbools']['blLoadVariants'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADVARIANTS'), $this);?>

             </td>
             <td valign="top" width="100%">
              <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADVARIANTS'), $this);?>

             </td>
            </tr>

            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top">
                <input type=hidden name=confbools[blUseTimeCheck] value=false>
                <input type=checkbox name=confbools[blUseTimeCheck] value=true  <?php if (( $this->_tpl_vars['confbools']['blUseTimeCheck'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_USETIMECHECKINARTLOAD'), $this);?>

             </td>
             <td valign="top" width="100%">
              <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_USETIMECHECKINARTLOAD'), $this);?>

             </td>
            </tr>

            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top" class="nowrap">
               <select class="confinput" name=confstrs[iTop5Mode] <?php echo $this->_tpl_vars['readonly']; ?>
>
                 <option value="0" <?php if ($this->_tpl_vars['confstrs']['iTop5Mode'] == 0): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_OFF'), $this);?>
</option>
                 <option value="1" <?php if ($this->_tpl_vars['confstrs']['iTop5Mode'] == 1): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MANUELL'), $this);?>
</option>
                 <option value="2" <?php if ($this->_tpl_vars['confstrs']['iTop5Mode'] == 2): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_AUTO'), $this);?>
</option>
               </select>
               <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_TOPSELLER'), $this);?>

             </td>
             <td valign="top" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_TOPSELLER'), $this);?>

             </td>
            </tr>

            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top">
               <select class="confinput" name=confstrs[iNewestArticlesMode] <?php echo $this->_tpl_vars['readonly']; ?>
>
                 <option value="0" <?php if ($this->_tpl_vars['confstrs']['iNewestArticlesMode'] == 0): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_OFF'), $this);?>
</option>
                 <option value="1" <?php if ($this->_tpl_vars['confstrs']['iNewestArticlesMode'] == 1): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MANUELL'), $this);?>
</option>
                 <option value="2" <?php if ($this->_tpl_vars['confstrs']['iNewestArticlesMode'] == 2): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_AUTO'), $this);?>
</option>
               </select>
               <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_NEWESTARTICLES'), $this);?>

             </td>
             <td valign="top" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_NEWESTARTICLES'), $this);?>

             </td>
            </tr>

            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top">
                <input type=hidden name=confbools[blDontShowEmptyCategories] value=false>
                <input type=checkbox class="confinput" name=confbools[blDontShowEmptyCategories] value=true  <?php if (( $this->_tpl_vars['confbools']['blDontShowEmptyCategories'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_DONTSHOWEMTYCATEGORIES'), $this);?>

             </td>
             <td valign="top" width="100%" >
               <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_DONTSHOWEMTYCATEGORIES'), $this);?>

             </td>
            </tr>

            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
             <td valign="top">
                <input type=hidden name=confbools[blClearCacheOnLogout] value=false>
                <input type=checkbox class="confinput" name=confbools[blClearCacheOnLogout] value=true  <?php if (( $this->_tpl_vars['confbools']['blClearCacheOnLogout'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_CLEARCACHEONLOGOUT'), $this);?>

             </td>
             <td valign="top" width="100%" >
               <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_CLEARCACHEONLOGOUT'), $this);?>

             </td>
            </tr>
            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
         		<td valign="top">
            		<input type="hidden" name="confbools[blEnableSeoCache]" value="false">
            		<input type="checkbox" class="confinput" name="confbools[blEnableSeoCache]" value="true"  <?php if (( $this->_tpl_vars['confbools']['blEnableSeoCache'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
            		<?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_SEO_CACHE'), $this);?>

        		</td>
         	<td valign="top" width="100%" >
           		<?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_SEO_CACHE'), $this);?>

         	</td>
        	</tr>
            <tr class="conftext<?php echo smarty_function_cycle(array(), $this);?>
">
                <td valign="top">
                    <input type="hidden" name="confbools[blCheckSysReq]" value="false">
                    <input type="checkbox" class="confinput" name="confbools[blCheckSysReq]" value="true"  <?php if (( $this->_tpl_vars['confbools']['blCheckSysReq'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_SYSREQ_CHECK'), $this);?>

                </td>
                <td valign="top" width="100%" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_SYSREQ_CHECK'), $this);?>

                </td>
            </tr>
        
    </table>
    <br>

    <fieldset title="<?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_PERFORMANCE'), $this);?>
">
    <legend><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_EXTERNALPERFORMANCE'), $this);?>
</legend><br>
    <table border=0>
    
        <tr>
            <td width="50%" valign="top">
                <table border=0 width="100%">

                <tr>
                 <td valign="top" class="conftext nowrap">
                    <input type=hidden name=confbools[bl_perfLoadAktion] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadAktion] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadAktion'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADACTION'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADACTION'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadCrossselling] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadCrossselling] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadCrossselling'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADCROSSSELLING'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADCROSSSELLING'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadAccessoires] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadAccessoires] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadAccessoires'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADACCESSOIRES'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADACCESSOIRES'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadCustomerWhoBoughtThis] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadCustomerWhoBoughtThis] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadCustomerWhoBoughtThis'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADCUSTOMERWHOBOUGHTTHIS'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADCUSTOMERWHOBOUGHTTHIS'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadSimilar] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadSimilar] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadSimilar'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADSIMILAR'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                 <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADSIMILAR'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadSelectLists] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadSelectLists] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadSelectLists'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADSELECTLIST'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADSELECTLIST'), $this);?>

                 </td>
                </tr>

                <tr>
                  <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadSelectListsInAList] value=false>
                    <input type=checkbox class="confinput" name=confbools[bl_perfLoadSelectListsInAList] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadSelectListsInAList'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADSELECTLISTSINALIST'), $this);?>

                  </td>
                  <td valign="top" class="conftext" width="100%" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADSELECTLISTSINALIST'), $this);?>

                  </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadManufacturerTree] value=false>
                    <input type=checkbox class="confinput" name=confbools[bl_perfLoadManufacturerTree] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadManufacturerTree'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADMANUFACTURERTREE'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%" >
                   <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADMANUFACTURERTREE'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfUseSelectlistPrice] value=false>
                    <input type=checkbox class="confinput" name=confbools[bl_perfUseSelectlistPrice] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfUseSelectlistPrice'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_USESELECTLISTPRICE'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%" >
                   <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_USESELECTLISTPRICE'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[blPerfNoBasketSaving] value=false>
                    <input type=checkbox class="confinput" name=confbools[blPerfNoBasketSaving] value=true  <?php if (( $this->_tpl_vars['confbools']['blPerfNoBasketSaving'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_DISBASKETSAVING'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%" >
                   <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_DISBASKETSAVING'), $this);?>

                 </td>
                </tr>

                </table>
            </td>

            <td valign="top">

                <table border=0 width="100%">

                <tr>
                 <td valign="top" class="conftext nowrap">
                    <input type=hidden name=confbools[bl_perfLoadDelivery] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadDelivery] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadDelivery'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADDELIVERY'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADDELIVERY'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadPrice] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadPrice] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadPrice'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADPRICE'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADPRICE'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadPriceForAddList] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadPriceForAddList] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadPriceForAddList'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADPRICEFORADDLIST'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADPRICEFORADDLIST'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfShowActionCatArticleCnt] value=false>
                    <input type=checkbox name=confbools[bl_perfShowActionCatArticleCnt] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfShowActionCatArticleCnt'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_SHOWACTCATARTCOUNT'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_SHOWACTCATARTCOUNT'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadCurrency] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadCurrency] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadCurrency'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADCURRENCY'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADCURRENCY'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadLanguages] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadLanguages] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadLanguages'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADLANGUAGES'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADLANGUAGES'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadNews] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadNews] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadNews'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADNEWS'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADNEWS'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfLoadNewsOnlyStart] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadNewsOnlyStart] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadNewsOnlyStart'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_LOADNEWSONLYSTART'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_LOADNEWSONLYSTART'), $this);?>

                 </td>
                </tr>

                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfParseLongDescinSmarty] value=false>
                    <input type=checkbox name=confbools[bl_perfParseLongDescinSmarty] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfParseLongDescinSmarty'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_PARSELONGDESCINSMARTY'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_PARSELONGDESCINSMARTY'), $this);?>

                 </td>
                </tr>
                <tr>
                 <td valign="top" class="conftext">
                    <input type=hidden name=confbools[bl_perfCalcVatOnlyForBasketOrder] value=false>
                    <input type=checkbox name=confbools[bl_perfCalcVatOnlyForBasketOrder] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfCalcVatOnlyForBasketOrder'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_PERF_CALCVATONLYFORBASKETORDER'), $this);?>

                 </td>
                 <td valign="top" class="conftext" width="100%">
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_PERF_CALCVATONLYFORBASKETORDER'), $this);?>

                 </td>
                </tr>
                </table>
            </td>
        </tr>
    
    </table>
    </fieldset>
        <br>
         <input type="submit" class="confinput" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'"" <?php echo $this->_tpl_vars['readonly']; ?>
>
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomnaviitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>