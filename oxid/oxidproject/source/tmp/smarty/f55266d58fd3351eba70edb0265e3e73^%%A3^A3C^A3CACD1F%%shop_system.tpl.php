<?php /* Smarty version 2.6.33, created on 2025-04-02 05:10:14
         compiled from shop_system.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'shop_system.tpl', 1, false),array('function', 'cycle', 'shop_system.tpl', 34, false),array('function', 'oxmultilang', 'shop_system.tpl', 57, false),array('function', 'oxinputhelp', 'shop_system.tpl', 62, false),)), $this); ?>
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
function editThis( sID )
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
    <input type="hidden" name="cl" value="shop_system">
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

    <input type="hidden" name="cl" value="shop_system">
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

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_ORDER'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input <?php echo $this->_tpl_vars['readonly']; ?>
 type=hidden name=confbools[blOtherCountryOrder] value=false>
                    <input type=checkbox name=confbools[blOtherCountryOrder] value=true  <?php if (( $this->_tpl_vars['confbools']['blOtherCountryOrder'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_OTHERCOUNTRYORDER'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_OTHERCOUNTRYORDER'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input <?php echo $this->_tpl_vars['readonly']; ?>
 type=hidden name=confbools[blDisableNavBars] value=false>
                    <input type=checkbox name=confbools[blDisableNavBars] value=true  <?php if (( $this->_tpl_vars['confbools']['blDisableNavBars'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_DISABLENAVBARS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_DISABLENAVBARS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStoreIPs] value=false>
                    <input type=checkbox name=confbools[blStoreIPs] value=true  <?php if (( $this->_tpl_vars['confbools']['blStoreIPs'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_STOREIPS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_STOREIPS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input <?php echo $this->_tpl_vars['readonly']; ?>
 type=hidden name=confbools[blOrderDisWithoutReg] value=false>
                    <input type=checkbox name=confbools[blOrderDisWithoutReg] value=true  <?php if (( $this->_tpl_vars['confbools']['blOrderDisWithoutReg'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_ORDERDISNOREG'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_ORDERDISNOREG'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_OPTIONS_GROUP_VARIANTS'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blVariantsSelection] value=false>
                    <input type=checkbox name=confbools[blVariantsSelection] value=true  <?php if (( $this->_tpl_vars['confbools']['blVariantsSelection'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_VARIANTSSELECTION'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_VARIANTSSELECTION'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blVariantParentBuyable] value=false>
                    <input type=checkbox name=confbools[blVariantParentBuyable] value=true  <?php if (( $this->_tpl_vars['confbools']['blVariantParentBuyable'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_VARIANTPARENTBUYABLE'), $this);?>

                </dt>
                <dd>
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_VARIANTPARENTBUYABLE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blVariantInheritAmountPrice] value=false>
                    <input type=checkbox class="confinput" name=confbools[blVariantInheritAmountPrice] value=true  <?php if (( $this->_tpl_vars['confbools']['blVariantInheritAmountPrice'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_VARIANTINHERITAMOUNTPRICE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_VARIANTINHERITAMOUNTPRICE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowVariantReviews] value=false>
                    <input type=checkbox class="confinput" name=confbools[blShowVariantReviews] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowVariantReviews'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_SHOWVARIANTREVIEWS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SHOWVARIANTREVIEWS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blUseMultidimensionVariants] value=false>
                    <input type=checkbox class="confinput" name=confbools[blUseMultidimensionVariants] value=true  <?php if (( $this->_tpl_vars['confbools']['blUseMultidimensionVariants'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_USEMULTIDIMENSIONVARIANTS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_USEMULTIDIMENSIONVARIANTS'), $this);?>

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
                    <input type=text  class="txt" name=confstrs[sDefaultImageQuality] value="<?php echo $this->_tpl_vars['confstrs']['sDefaultImageQuality']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_DEFAULTIMAGEQUALITY'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_DEFAULTIMAGEQUALITY'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input <?php echo $this->_tpl_vars['readonly']; ?>
 type=hidden name=confbools[blInlineImgEmail] value=false>
                    <input type=checkbox name=confbools[blInlineImgEmail] value=true  <?php if (( $this->_tpl_vars['confbools']['blInlineImgEmail'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_INLINEIMGEMAIL'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_INLINEIMGEMAIL'), $this);?>

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
                    <textarea class="txtfield" name=confaarrs[aInterfaceProfiles] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confaarrs']['aInterfaceProfiles']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_INTERFACEPROFILES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_INTERFACEPROFILES'), $this);?>

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
                    <input type=text  class="txt" name=confstrs[iServerTimeShift] value="<?php echo $this->_tpl_vars['confstrs']['iServerTimeShift']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_ISERVERTIMESHIFT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_ISERVERTIMESHIFT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowRememberMe] value=false>
                    <input type=checkbox name=confbools[blShowRememberMe] value=true  <?php if (( $this->_tpl_vars['confbools']['blShowRememberMe'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_SHOWREMEMBERME'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SHOWREMEMBERME'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iAttributesPercent] value="<?php echo $this->_tpl_vars['confstrs']['iAttributesPercent']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_ATTRIBUTESPERCENT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_ATTRIBUTESPERCENT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blGBModerate] value=false>
                    <input type=checkbox name=confbools[blGBModerate] value=true  <?php if (( $this->_tpl_vars['confbools']['blGBModerate'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_GBMODERATE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_GBMODERATE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include/ldap_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aLogSkipTags] <?php echo $this->_tpl_vars['readonly']; ?>
><?php echo $this->_tpl_vars['confarrs']['aLogSkipTags']; ?>
</textarea>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_LOGSKIPTAGS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_LOGSKIPTAGS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <?php if (! $this->_tpl_vars['isdemoshop']): ?>
            <dl>
                <dt>
                    <select name=confstrs[iSmartyPhpHandling] <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="<?php echo @SMARTY_PHP_PASSTHRU; ?>
"  <?php if ($this->_tpl_vars['confstrs']['iSmartyPhpHandling'] == @SMARTY_PHP_PASSTHRU): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SMARTYPHPHANDLING_REMOVE'), $this);?>
</option>
                        <option value="<?php echo @SMARTY_PHP_QUOTE; ?>
"  <?php if ($this->_tpl_vars['confstrs']['iSmartyPhpHandling'] == @SMARTY_PHP_QUOTE): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SMARTYPHPHANDLING_PASSTHRU'), $this);?>
</option>
                        <option value="<?php echo @SMARTY_PHP_REMOVE; ?>
"  <?php if ($this->_tpl_vars['confstrs']['iSmartyPhpHandling'] == @SMARTY_PHP_REMOVE): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SMARTYPHPHANDLING_QUOTE'), $this);?>
</option>
                        <option value="<?php echo @SMARTY_PHP_ALLOW; ?>
"  <?php if ($this->_tpl_vars['confstrs']['iSmartyPhpHandling'] == @SMARTY_PHP_ALLOW): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SMARTYPHPHANDLING_ALLOW'), $this);?>
</option>
                    </select>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_SMARTYPHPHANDLING'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <?php endif; ?>

            <dl>
                <dt>
                    <input type=text class="txt" style="width: 430px;" name=confstrs[sUtilModule] value="<?php echo $this->_tpl_vars['confstrs']['sUtilModule']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_SYSTEM_UTILMODULE'), $this);?>

                </dt>
                <dd>
                  <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_SYSTEM_UTILMODULE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

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