<?php /* Smarty version 2.6.33, created on 2025-04-03 13:11:55
         compiled from shop_config.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'shop_config.tpl', 1, false),array('modifier', 'escape', 'shop_config.tpl', 659, false),array('function', 'cycle', 'shop_config.tpl', 58, false),array('function', 'oxmultilang', 'shop_config.tpl', 81, false),array('function', 'oxinputhelp', 'shop_config.tpl', 89, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
function _groupExp(el) {
    var _cur = el.parentNode;

    if (_cur.className == "exp") _cur.className = "";
      else _cur.className = "exp";
}
function showBasketReserved()
{
  if( document.getElementById('basketreserved').value == 1)
  {
    document.getElementById('basketreservedtime').className = 'rowexp';
  }
  else
  {
    document.getElementById('basketreservedtime').className = 'rowhide';
  }
}
function showInvitations()
{
  if( document.getElementById('invitations').value == 1)
  {
    document.getElementById('pointsforinvitation').className = 'rowexp';
    document.getElementById('pointsforregistration').className = 'rowexp';
  }
  else
  {
    document.getElementById('pointsforinvitation').className = 'rowhide';
    document.getElementById('pointsforregistration').className = 'rowhide';
  }
}
function editThis(sID)
{
    var oTransfer = top.basefrm.edit.document.getElementById("transfer");
    oTransfer.oxid.value = '';
    oTransfer.cl.value = top.oxid.admin.getClass( sID );

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
    <input type="hidden" name="cl" value="shop_config">
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

<input type="hidden" name="cl" value="shop_config">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
<input type="hidden" name="editval[oxshops__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include/update_views_notice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_GLOBAL'), $this);?>
</b></a>
            <dl>
                <dt>
                    <select class="select" multiple size="4" name=confarrs[aHomeCountry][] <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php $_from = $this->_tpl_vars['countrylist']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oCountry']):
?>
                        <option value="<?php echo $this->_tpl_vars['oCountry']->oxcountry__oxid->value; ?>
"<?php if ($this->_tpl_vars['oCountry']->selected): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['oCountry']->oxcountry__oxtitle->value; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_INLANDCUSTOMERS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_INLANDCUSTOMERS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_SEARCH'), $this);?>
</b></a>
          
            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aSearchCols] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confarrs']['aSearchCols']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SEARCHFIELDS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SEARCHFIELDS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blSearchUseAND] value=false>
                    <input type=checkbox name=confbools[blSearchUseAND] value=true  <?php if (( $this->_tpl_vars['confbools']['blSearchUseAND'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SEARCHUSEAND'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_STOCK'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blUseStock] value=false>
                    <input type=checkbox name=confbools[blUseStock] value=true  <?php if (( $this->_tpl_vars['confbools']['blUseStock'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_USESTOCK'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_USESTOCK'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowNegativeStock] value=false>
                    <input type=checkbox name=confbools[blAllowNegativeStock] value=true  <?php if (( $this->_tpl_vars['confbools']['blAllowNegativeStock'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_USENEGATIVESTOCK'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_USENEGATIVESTOCK'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input type=text class="txt" name=confstrs[sStockWarningLimit] value="<?php echo $this->_tpl_vars['confstrs']['sStockWarningLimit']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_STOCKWARNINGLIMIT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_STOCKWARNINGLIMIT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStockOnDefaultMessage] value=false>
                    <input type=checkbox name=confbools[blStockOnDefaultMessage] value=true  <?php if (( $this->_tpl_vars['confbools']['blStockOnDefaultMessage'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_STOCKONDEFAULTMESSAGE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_STOCKONDEFAULTMESSAGE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStockOffDefaultMessage] value=false>
                    <input type=checkbox name=confbools[blStockOffDefaultMessage] value=true  <?php if (( $this->_tpl_vars['confbools']['blStockOffDefaultMessage'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_STOCKOFFDEFAULTMESSAGE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_STOCKOFFDEFAULTMESSAGE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_ARTICLES'), $this);?>
</b></a>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofSimilarArticles] value="<?php echo $this->_tpl_vars['confstrs']['iNrofSimilarArticles']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_NUMBEROFSIMILARARTICLES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_NUMBEROFSIMILARARTICLES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofCustomerWhoArticles] value="<?php echo $this->_tpl_vars['confstrs']['iNrofCustomerWhoArticles']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_NROFCUSTOMERWHOARTICLES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_NROFCUSTOMERWHOARTICLES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofNewcomerArticles] value="<?php echo $this->_tpl_vars['confstrs']['iNrofNewcomerArticles']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_NROFNEWCOMERARTICLES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_NROFNEWCOMERARTICLES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofCrossellArticles] value="<?php echo $this->_tpl_vars['confstrs']['iNrofCrossellArticles']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_NUMBEROFCROSSSELLARTICLES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_NUMBEROFCROSSSELLARTICLES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowSorting] value=false>
                    <input type=checkbox name=confbools[blShowSorting] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowSorting'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SORTITEMSLIST'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SORTITEMSLIST'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aSortCols] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confarrs']['aSortCols']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SORTFIELDS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SORTFIELDS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blOverrideZeroABCPrices] value=false>
                    <input type=checkbox name=confbools[blOverrideZeroABCPrices] value=true  <?php if (( $this->_tpl_vars['confbools']['blOverrideZeroABCPrices'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_OVERRIDEZEROABCPRICES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_OVERRIDEZEROABCPRICES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blWarnOnSameArtNums] value=false>
                    <input type=checkbox name=confbools[blWarnOnSameArtNums] value=true <?php if (( $this->_tpl_vars['confbools']['blWarnOnSameArtNums'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_WARNONSAMEARTNUMS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_WARNONSAMEARTNUMS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blNewArtByInsert] value=false>
                    <input type=checkbox name=confbools[blNewArtByInsert] value=true  <?php if (( $this->_tpl_vars['confbools']['blNewArtByInsert'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_NEWARTBYINSERT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_NEWARTBYINSERT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blDisableDublArtOnCopy] value=false>
                    <input type=checkbox name=confbools[blDisableDublArtOnCopy] value=true <?php if (( $this->_tpl_vars['confbools']['blDisableDublArtOnCopy'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DISABLEARTDUBLICATES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DISABLEARTDUBLICATES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowSuggestArticle] value=false>
                    <input type=checkbox name=confbools[blAllowSuggestArticle] value=true <?php if (( $this->_tpl_vars['confbools']['blAllowSuggestArticle'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ALLOW_SUGGEST_ARTICLE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ALLOW_SUGGEST_ARTICLE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[bl_perfLoadReviews] value=false>
                    <input type=checkbox name=confbools[bl_perfLoadReviews] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_perfLoadReviews'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_LOADREVIEWS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_LOADREVIEWS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[includeProductReviewLinksInEmail] value=false>
                    <input type=checkbox name=confbools[includeProductReviewLinksInEmail] value=true  <?php if (( $this->_tpl_vars['confbools']['includeProductReviewLinksInEmail'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_INCLUDE_PRODUCT_REVIEW_LINKS_IN_ORDER_EMAIL'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_INCLUDE_PRODUCT_REVIEW_LINKS_IN_ORDER_EMAIL'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_ORDER'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sMidlleCustPrice] value="<?php echo $this->_tpl_vars['confstrs']['sMidlleCustPrice']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_MIDLLECUSTOMERPRICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_MIDLLECUSTOMERPRICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sLargeCustPrice] value="<?php echo $this->_tpl_vars['confstrs']['sLargeCustPrice']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_LARGECUSTOMERPRICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_LARGECUSTOMERPRICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowUnevenAmounts] value=false>
                    <input type=checkbox name=confbools[blAllowUnevenAmounts] value=true  <?php if (( $this->_tpl_vars['confbools']['blAllowUnevenAmounts'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ALLOWUNEVENAMOUNTS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ALLOWUNEVENAMOUNTS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMinOrderPrice] value="<?php echo $this->_tpl_vars['confstrs']['iMinOrderPrice']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_MINORDERPRICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_MINORDERPRICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowOrderButtonOnTop] value=false>
                    <input type=checkbox name=confbools[blShowOrderButtonOnTop] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowOrderButtonOnTop'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SHOWORDERBUTTONONTHETOP'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SHOWORDERBUTTONONTHETOP'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blConfirmAGB] value=false>
                    <input type=checkbox name=confbools[blConfirmAGB] value=true  <?php if (( $this->_tpl_vars['confbools']['blConfirmAGB'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CONFIRMAGB'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CONFIRMAGB'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blEnableIntangibleProdAgreement] value=false>
                    <input type=checkbox name=confbools[blEnableIntangibleProdAgreement] value=true  <?php if (( $this->_tpl_vars['confbools']['blEnableIntangibleProdAgreement'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ENABLE_INTANGIBLE_PRODUCTS_AGREEMENT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ENABLE_INTANGIBLE_PRODUCTS_AGREEMENT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStoreCreditCardInfo] value=false>
                    <input type=checkbox name=confbools[blStoreCreditCardInfo] value=true  <?php if (( $this->_tpl_vars['confbools']['blStoreCreditCardInfo'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_STORECREDITCARDINFO'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_STORECREDITCARDINFO'), $this);?>
 <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ATTENTION'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowTSInternationalFeesMessage] value=false>
                    <input type=checkbox name=confbools[blShowTSInternationalFeesMessage] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowTSInternationalFeesMessage'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SHOWTSINTERNATIONALFEESMESSAGE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SHOWTSINTERNATIONALFEESMESSAGE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowTSCODMessage] value=false>
                    <input type=checkbox name=confbools[blShowTSCODMessage] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowTSCODMessage'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SHOWTSCODMESSAGE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SHOWTSCODMESSAGE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_VAT'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=text class="txt" style="width:70" name=confnum[dDefaultVAT] value="<?php echo $this->_tpl_vars['confnum']['dDefaultVAT']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DEFAULTVAT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DEFAULTVAT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input type=hidden name=confbools[blEnterNetPrice] value=false>
            <input type=checkbox name=confbools[blEnterNetPrice] value=true  <?php if (( $this->_tpl_vars['confbools']['blEnterNetPrice'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ENTERNETPRICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ENTERNETPRICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input type=hidden name=confbools[blShowNetPrice] value=false>
            <input type=checkbox name=confbools[blShowNetPrice] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowNetPrice'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
            <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_VIEWNETPRICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_VIEWNETPRICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>


            <dl>
                <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ADDITIONAL_SERVICE_VAT_CALCULATION_METHOD'), $this);?>
:<br>
                <dd>
                    <input type="radio" name="confstrs[sAdditionalServVATCalcMethod]" value="biggest_net"  <?php if (( $this->_tpl_vars['confstrs']['sAdditionalServVATCalcMethod'] == 'biggest_net' )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ADDITIONAL_SERVICE_VAT_CALCULATION_BIGGEST_NET'), $this);?>

                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ADDITIONAL_SERVICE_VAT_CALCULATION_BIGGEST_NET'), $this);?>

                        <br>
                    <input type="radio" name="confstrs[sAdditionalServVATCalcMethod]" value="proportional"  <?php if (( $this->_tpl_vars['confstrs']['sAdditionalServVATCalcMethod'] == 'proportional' )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ADDITIONAL_SERVICE_VAT_CALCULATION_PROPORTIONAL'), $this);?>

                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ADDITIONAL_SERVICE_VAT_CALCULATION_PROPORTIONAL'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowVATForDelivery] value=false>
                    <input type=checkbox name=confbools[blShowVATForDelivery] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowVATForDelivery'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CALCULATEVATFORDELIVERY'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CALCULATEVATFORDELIVERY'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blDeliveryVatOnTop] value=false>
                    <input type=checkbox name=confbools[blDeliveryVatOnTop] value=true  <?php if (( $this->_tpl_vars['confbools']['blDeliveryVatOnTop'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CALCDELVATONTOP'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CALCDELVATONTOP'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowVATForPayCharge] value=false>
                    <input type=checkbox name=confbools[blShowVATForPayCharge] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowVATForPayCharge'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CALCULATEVATOFORPAYCHARGE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CALCULATEVATOFORPAYCHARGE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blPaymentVatOnTop] value=false>
                    <input type=checkbox name=confbools[blPaymentVatOnTop] value=true  <?php if (( $this->_tpl_vars['confbools']['blPaymentVatOnTop'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CALCPAYVATONTOP'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CALCPAYVATONTOP'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowVATForWrapping] value=false>
                    <input type=checkbox name=confbools[blShowVATForWrapping] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowVATForWrapping'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CALCULATEVATFORWRAPPING'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CALCULATEVATFORWRAPPING'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blWrappingVatOnTop] value=false>
                    <input type=checkbox name=confbools[blWrappingVatOnTop] value=true  <?php if (( $this->_tpl_vars['confbools']['blWrappingVatOnTop'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CALCWRAPVATONTOP'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CALCWRAPVATONTOP'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShippingCountryVat] value=false>
                    <input type=checkbox name=confbools[blShippingCountryVat] value=true  <?php if (( $this->_tpl_vars['confbools']['blShippingCountryVat'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SHIPPINGCOUNTRYVAT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SHIPPINGCOUNTRYVAT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blVatIdCheckDisabled] value=false>
                    <input type=checkbox name=confbools[blVatIdCheckDisabled] value=true  <?php if (( $this->_tpl_vars['confbools']['blVatIdCheckDisabled'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DISABLEONLINEVATIDCHECK'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DISABLEONLINEVATIDCHECK'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="editinput" size="35" name=confstrs[sVatIdCheckInterfaceWsdl] value="<?php echo $this->_tpl_vars['confstrs']['sVatIdCheckInterfaceWsdl']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ALTVATIDCHECKINTERFACEWSDL'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ALTVATIDCHECKINTERFACEWSDL'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_PICTURES'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blAutoIcons] value=false>
                    <input type=checkbox name=confbools[blAutoIcons] value=true  <?php if (( $this->_tpl_vars['confbools']['blAutoIcons'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_AUTOICONS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_AUTOICONS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_SHOP_FRONTEND'), $this);?>
</b></a>

            <dl>
                <dt>
                    <input type="button" value="<?php if (isset ( $this->_tpl_vars['defcat'] ) && isset ( $this->_tpl_vars['defcat']->oxcategories__oxtitle )): ?><?php echo $this->_tpl_vars['defcat']->oxcategories__oxtitle->value; ?>
<?php else: ?>---<?php endif; ?>" onclick="JavaScript:showDialog('&cl=shop_config&aoc=1&oxid=<?php echo ((is_array($_tmp=$this->_tpl_vars['oxid'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
');" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ACTIVECATEGORYBYSTART'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ACTIVECATEGORYBYSTART'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCntOfNewsLoaded] value="<?php echo $this->_tpl_vars['confstrs']['sCntOfNewsLoaded']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CNTOFNEWS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CNTOFNEWS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_PRIVATESALES'), $this);?>
</b></a>
            <dl>
                <dt>
                    <select class="select" name=confstrs[blPsLoginEnabled] <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0"  <?php if (! $this->_tpl_vars['confstrs']['blPsLoginEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DISABLE'), $this);?>
</option>
                        <option value="1"  <?php if ($this->_tpl_vars['confstrs']['blPsLoginEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ENABLE'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_PSLOGIN'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_PSLOGIN'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select class="select" name=confstrs[blBasketExcludeEnabled] <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0"  <?php if (! $this->_tpl_vars['confstrs']['blBasketExcludeEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DISABLE'), $this);?>
</option>
                        <option value="1"  <?php if ($this->_tpl_vars['confstrs']['blBasketExcludeEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ENABLE'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_BASKETEXCLUDE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_BASKETEXCLUDE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select class="select" id="basketreserved" name=confstrs[blPsBasketReservationEnabled] onchange="javascript:showBasketReserved();" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0"  <?php if (! $this->_tpl_vars['confstrs']['blPsBasketReservationEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DISABLE'), $this);?>
</option>
                        <option value="1"  <?php if ($this->_tpl_vars['confstrs']['blPsBasketReservationEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ENABLE'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_BASKETRESERVATION'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_BASKETRESERVATION'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl <?php if (! $this->_tpl_vars['confstrs']['blPsBasketReservationEnabled']): ?>class="rowhide"<?php endif; ?> id="basketreservedtime">
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[iPsBasketReservationTimeout] value="<?php if ($this->_tpl_vars['confstrs']['iPsBasketReservationTimeout']): ?><?php echo $this->_tpl_vars['confstrs']['iPsBasketReservationTimeout']; ?>
<?php else: ?>1200<?php endif; ?>" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_BASKETRESERVATIONTIMEOUT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_BASKETRESERVATIONTIMEOUT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_INVITATIONS'), $this);?>
</b></a>
            <dl>
                <dt>
                    <select class="select" id="invitations" name=confstrs[blInvitationsEnabled] onchange="javascript:showInvitations();" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0"  <?php if (! $this->_tpl_vars['confstrs']['blInvitationsEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DISABLE'), $this);?>
</option>
                        <option value="1"  <?php if ($this->_tpl_vars['confstrs']['blInvitationsEnabled']): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ENABLE'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_INVITATION'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_INVITATION'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl <?php if (! $this->_tpl_vars['confstrs']['blInvitationsEnabled']): ?>class="rowhide"<?php endif; ?> id="pointsforinvitation">
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[dPointsForInvitation] value="<?php echo $this->_tpl_vars['confstrs']['dPointsForInvitation']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_POINTSFORINVITATION'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_POINTSFORINVITATION'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl <?php if (! $this->_tpl_vars['confstrs']['blInvitationsEnabled']): ?>class="rowhide"<?php endif; ?> id="pointsforregistration">
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[dPointsForRegistration] value="<?php echo $this->_tpl_vars['confstrs']['dPointsForRegistration']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_POINTSFORREGISTRATION'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_POINTSFORREGISTRATION'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_SHOP_DOWNLOADABLEARTICLES'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blEnableDownloads] value=false>
                    <input type=checkbox name=confbools[blEnableDownloads] value=true  <?php if (( $this->_tpl_vars['confbools']['blEnableDownloads'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DOWNLOADS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DOWNLOADS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" style="width: 250px;" name=confstrs[sDownloadsDir] value="<?php echo $this->_tpl_vars['confstrs']['sDownloadsDir']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DOWNLOADS_PATH'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DOWNLOADS_PATH'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMaxDownloadsCount] value="<?php echo $this->_tpl_vars['confstrs']['iMaxDownloadsCount']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_MAX_DOWNLOADS_COUNT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_DOWNLOADS_COUNT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iLinkExpirationTime] value="<?php echo $this->_tpl_vars['confstrs']['iLinkExpirationTime']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_LINK_EXPIRATION_TIME'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_LINK_EXPIRATION_TIME'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iDownloadExpirationTime] value="<?php echo $this->_tpl_vars['confstrs']['iDownloadExpirationTime']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DOWNLOAD_EXPIRATION_TIME'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_DOWNLOAD_EXPIRATION_TIME'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMaxDownloadsCountUnregistered] value="<?php echo $this->_tpl_vars['confstrs']['iMaxDownloadsCountUnregistered']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_LINK_EXPIRATION_TIME_UNREGISTERED'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_LINK_EXPIRATION_TIME_UNREGISTERED'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_ADMINISTRATION'), $this);?>
</b></a>
            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aCMSfolder] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confaarrs']['aCMSfolder']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CMSFOLDER'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CMSFOLDER'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blOrderOptInEmail] value=false>
                    <input type=checkbox name=confbools[blOrderOptInEmail] value=true  <?php if (( $this->_tpl_vars['confbools']['blOrderOptInEmail'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ORDEROPTINEMAIL'), $this);?>

                </dt>
                <dd>
                   <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ORDEROPTINEMAIL'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aOrderfolder] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confaarrs']['aOrderfolder']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_ORDERFOLDER'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ORDERFOLDER'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sLocalDateFormat]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="ISO" <?php if ($this->_tpl_vars['confstrs']['sLocalDateFormat'] == 'ISO'): ?>selected<?php endif; ?>>ISO: YYYY-MM-DD</option>
                        <option value="EUR" <?php if ($this->_tpl_vars['confstrs']['sLocalDateFormat'] == 'EUR'): ?>selected<?php endif; ?>>EUR: DD.MM.YYYY</option>
                        <option value="USA" <?php if ($this->_tpl_vars['confstrs']['sLocalDateFormat'] == 'USA'): ?>selected<?php endif; ?>>USA: MM/DD/YYYY</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DATEFORMAT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DATEFORMAT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sLocalTimeFormat]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="ISO" <?php if ($this->_tpl_vars['confstrs']['sLocalTimeFormat'] == 'ISO'): ?>selected<?php endif; ?>>ISO: HH:MM:SS</option>
                        <option value="EUR" <?php if ($this->_tpl_vars['confstrs']['sLocalTimeFormat'] == 'EUR'): ?>selected<?php endif; ?>>EUR: HH.MM.SS</option>
                        <option value="USA" <?php if ($this->_tpl_vars['confstrs']['sLocalTimeFormat'] == 'USA'): ?>selected<?php endif; ?>>USA: HH:MM:SS AM (PM)</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_TIMEFORMAT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_TIMEFORMAT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <?php if ($this->_tpl_vars['oView']->informationSendingToOxidConfigurable()): ?>
            <dl>
                <dt>
                    <input type=hidden name="confbools[blSendTechnicalInformationToOxid]" value="false">
                    <input type=checkbox name="confbools[blSendTechnicalInformationToOxid]" value="true"  <?php if (( $this->_tpl_vars['confbools']['blSendTechnicalInformationToOxid'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SEND_TECHNICAL_INFORMATION_TO_OXID'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SEND_TECHNICAL_INFORMATION_TO_OXID'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <?php endif; ?>

            <dl>
                <dt>
                    <input type=hidden name="confbools[blCheckForUpdates]" value="false">
                    <input type=checkbox name="confbools[blCheckForUpdates]" value="true"  <?php if (( $this->_tpl_vars['confbools']['blCheckForUpdates'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CHECK_UPDATES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_OTHER_SETTINGS'), $this);?>
</b></a>
            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aMustFillFields] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confarrs']['aMustFillFields']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_MUSTFILLFIELDS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_MUSTFILLFIELDS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_FIELDS_CONTACTFORM'), $this);?>

                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_FIELDS_CONTACTFORM'), $this);?>

                    <input type="hidden" name="confarrs[contactFormRequiredFields][]" value="">
                    <?php $_from = $this->_tpl_vars['contactFormFieldConfigurations']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['formFieldConfiguration']):
?>
                    <div style="margin-left:10px;">
                        <input type="checkbox" name="confarrs[contactFormRequiredFields][]" value=<?php echo $this->_tpl_vars['formFieldConfiguration']['name']; ?>
 <?php if ($this->_tpl_vars['formFieldConfiguration']['isRequired']): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
 <?php echo $this->_tpl_vars['disabled']; ?>
>
                        <label><?php echo smarty_function_oxmultilang(array('ident' => $this->_tpl_vars['formFieldConfiguration']['label']), $this);?>
</label>
                        <br />
                    </div>
                    <?php endforeach; endif; unset($_from); ?>
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aCurrencies] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confarrs']['aCurrencies']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_SETORDELETECURRENCY'), $this);?>

                </dt>
                <dd>
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_SETORDELETECURRENCY'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blExclNonMaterialFromDelivery] value=false>
                    <input type=checkbox name=confbools[blExclNonMaterialFromDelivery] value=true  <?php if (( $this->_tpl_vars['confbools']['blExclNonMaterialFromDelivery'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_EXCLUDENONMATERIALPRODUCTS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_EXCLUDENONMATERIALPRODUCTS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blBidirectCross] value=false>
                    <input type=checkbox name=confbools[blBidirectCross] value=true  <?php if (( $this->_tpl_vars['confbools']['blBidirectCross'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_BIDIRECTCROSS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_BIDIRECTCROSS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRatingLogsTimeout] value="<?php echo $this->_tpl_vars['confstrs']['iRatingLogsTimeout']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DELETERATINGLOGS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DELETERATINGLOGS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRssItemsCount] value="<?php echo $this->_tpl_vars['confstrs']['iRssItemsCount']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSITEMSCOUNT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSITEMSCOUNT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                </dt>
                <dd>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSSELECTED'), $this);?>
<?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSSELECTED'), $this);?>

                    <div style="margin-left:10px;">
                        <input type=hidden name=confbools[bl_rssTopShop] value=false>
                        <input type=checkbox name=confbools[bl_rssTopShop] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssTopShop'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSTOPSHOP'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSTOPSHOP'), $this);?>

                        <br />

                        <input type=hidden name=confbools[bl_rssBargain] value=false>
                        <input type=checkbox name=confbools[bl_rssBargain] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssBargain'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSBARGAIN'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSBARGAIN'), $this);?>

                        <br />

                        <input type=hidden name=confbools[bl_rssNewest] value=false>
                        <input type=checkbox name=confbools[bl_rssNewest] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssNewest'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSNEWEST'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSNEWEST'), $this);?>

                        <br />

                        <input type=hidden name=confbools[bl_rssCategories] value=false>
                        <input type=checkbox name=confbools[bl_rssCategories] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssCategories'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSCATEGORIES'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSCATEGORIES'), $this);?>

                        <br />

                        <input type=hidden name=confbools[bl_rssSearch] value=false>
                        <input type=checkbox name=confbools[bl_rssSearch] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssSearch'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSSEARCH'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSSEARCH'), $this);?>

                        <br />

                        <input type=hidden name=confbools[bl_rssRecommLists] value=false>
                        <input type=checkbox name=confbools[bl_rssRecommLists] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssRecommLists'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSARTRECOMMLISTS'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSARTRECOMMLISTS'), $this);?>

                        <br />

                        <input type=hidden name=confbools[bl_rssRecommListArts] value=false>
                        <input type=checkbox name=confbools[bl_rssRecommListArts] value=true  <?php if (( $this->_tpl_vars['confbools']['bl_rssRecommListArts'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_RSSRECOMMLISTARTS'), $this);?>

                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_RSSRECOMMLISTARTS'), $this);?>

                    </div>
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blCalculateDelCostIfNotLoggedIn] value=false>
                    <input type=checkbox name=confbools[blCalculateDelCostIfNotLoggedIn] value=true  <?php if (( $this->_tpl_vars['confbools']['blCalculateDelCostIfNotLoggedIn'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DELIVERYCOSTS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DELIVERYCOSTS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sGiCsvFieldEncloser] value="<?php echo $this->_tpl_vars['confstrs']['sGiCsvFieldEncloser']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CSVFIELDENCLOSER'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCSVSign] value="<?php echo $this->_tpl_vars['confstrs']['sCSVSign']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CSVSEPARATOR'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CSVSEPARATOR'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iExportNrofLines] value="<?php echo $this->_tpl_vars['confstrs']['iExportNrofLines']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_EXPORTNUMBEROFLINES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_EXPORTNUMBEROFLINES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iCntofMails] value="<?php echo $this->_tpl_vars['confstrs']['iCntofMails']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_NUMBEROFEMAILSPERTICK'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_NUMBEROFEMAILSPERTICK'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowCookiesNotification] value=false>
                    <input type=checkbox name=confbools[blShowCookiesNotification] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowCookiesNotification'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_CONFIRMCOOKIE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_CONFIRMCOOKIE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type="text" class="txt" name="confstrs[sParcelService]" style="width: 300px;" value="<?php echo $this->_tpl_vars['confstrs']['sParcelService']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_PARCELSERVICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_PARCELSERVICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_BANK_INFORMATION'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blSkipDebitOldBankInfo] value=false>
                    <input type=checkbox name=confbools[blSkipDebitOldBankInfo] value=true  <?php if (( $this->_tpl_vars['confbools']['blSkipDebitOldBankInfo'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_CONFIG_DEBIT_OLD_BANK_INFORMATION_NOT_ALLOWED'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_DEBIT_OLD_BANK_INFORMATION_NOT_ALLOWED'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>
    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_ACCOUNT_SETTINGS'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowUsersToDeleteTheirAccount] value=false>
                    <input
                        type=checkbox
                        name=confbools[blAllowUsersToDeleteTheirAccount]
                        value=true <?php if (( $this->_tpl_vars['confbools']['blAllowUsersToDeleteTheirAccount'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>

                    >
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ALLOW_USERS_TO_DELETE_THEIR_ACCOUNT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowUsersToManageTheirReviews] value=false>
                    <input
                            type=checkbox
                            name=confbools[blAllowUsersToManageTheirReviews]
                            value=true <?php if (( $this->_tpl_vars['confbools']['blAllowUsersToManageTheirReviews'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>

                    >
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_CONFIG_ALLOW_USERS_MANAGE_REVIEWS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
        </div>
    </div>


    <br>

    <input type="submit" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'" <?php echo $this->_tpl_vars['readonly']; ?>
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